<?php
/**
 * Copyright (c) 2015-2020 Virgil Security Inc.
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *     (1) Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *     (2) Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *     (3) Neither the name of the copyright holder nor the names of its
 *     contributors may be used to endorse or promote products derived from
 *     this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ''AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING
 * IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Lead Maintainer: Virgil Security Inc. <support@virgilsecurity.com>
 */

namespace Virgil\PureKit\Pure;

use Virgil\Crypto\Core\Enum\HashAlgorithms;
use Virgil\Crypto\Core\Enum\KeyPairType;
use Virgil\Crypto\Core\VirgilKeys\VirgilKeyPair;
use Virgil\Crypto\Core\VirgilKeys\VirgilPrivateKey;
use Virgil\Crypto\Core\VirgilKeys\VirgilPublicKey;
use Virgil\Crypto\Core\VirgilKeys\VirgilPublicKeyCollection;
use Virgil\Crypto\VirgilCrypto;
use Virgil\CryptoWrapper\Phe\PheCipher;
use Virgil\PureKit\Pure\Exception\ErrorStatus\PureCryptoErrorStatus;
use Virgil\PureKit\Pure\Exception\PureCryptoException;
use Virgil\CryptoWrapper\Foundation\Aes256Gcm;
use Virgil\CryptoWrapper\Foundation\MessageInfoDerSerializer;
use Virgil\CryptoWrapper\Foundation\MessageInfoEditor;
use Virgil\CryptoWrapper\Foundation\RecipientCipher;
use Virgil\CryptoWrapper\Foundation\Sha512;

/**
 * Class PureCrypto
 * @package Virgil\PureKit\Pure
 */
class PureCrypto
{
    /**
     * @var VirgilCrypto
     */
    private $crypto;

    /**
     * @var PheCipher
     */
    private $pheCipher;

    public const DERIVED_SECRET_LENGTH = 44;

    /**
     * PureCrypto constructor.
     * @param VirgilCrypto $crypto
     * @throws PureCryptoException
     */
    public function __construct(VirgilCrypto $crypto)
    {
        $this->crypto = $crypto;

        try {
            $this->pheCipher = new PheCipher();
            $this->pheCipher->useRandom($crypto->getRng());
        } catch (\PheException $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $plainTextData
     * @param VirgilPublicKeyCollection $recipients
     * @param VirgilPrivateKey $signingKey
     * @return PureCryptoData
     * @throws PureCryptoException
     */
    public function encryptCellKey(string $plainTextData, VirgilPublicKeyCollection $recipients, VirgilPrivateKey $signingKey): PureCryptoData
    {
        try {
            $aesGsm = new Aes256Gcm();
            $cipher = new RecipientCipher();
            $sha512 = new Sha512();

            $cipher->useEncryptionCipher($aesGsm);
            $cipher->useRandom($this->crypto->getRng());

            $cipher->addSigner($signingKey->getIdentifier(), $signingKey->getPrivateKey());

            foreach ($recipients->getAsArray() as $key) {
                $cipher->addKeyRecipient($key->getIdentifier(), $key->getPublicKey());
            }

            $cipher->useSignerHash($sha512);
            $cipher->startSignedEncryption(strlen($plainTextData));

            $cms = $cipher->packMessageInfo();
            $body1 = $cipher->processEncryption($plainTextData);
            $body2 = $cipher->finishEncryption();
            $body3 = $cipher->packMessageInfoFooter();

            $body = $this->concat($this->concat($body1, $body2), $body3);

            return new PureCryptoData($cms, $body);


        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param PureCryptoData $data
     * @param VirgilPublicKey $verifyingKey
     * @param VirgilPrivateKey $privateKey
     * @return string
     * @throws PureCryptoException
     */
    public function decryptCellKey(PureCryptoData $data, VirgilPrivateKey $privateKey, VirgilPublicKey $verifyingKey):
    string
    {
        try {
            $cipher = new RecipientCipher();

            $cipher->useRandom($this->crypto->getRng());

            $cipher->startVerifiedDecryptionWithKey($privateKey->getIdentifier(), $privateKey->getPrivateKey(),
                $data->getCms(), "");

            $body1 = $cipher->processDecryption($data->getBody());
            $body2 = $cipher->finishDecryption();

            if (!$cipher->isDataSigned())
                throw new PureCryptoException(PureCryptoErrorStatus::SIGNATURE_IS_ABSENT());

            $signerInfoList = $cipher->signerInfos();

            if (!$signerInfoList->hasItem() && $signerInfoList->hasNext())
                throw new PureCryptoException(PureCryptoErrorStatus::SIGNER_IS_ABSENT());

            $signerInfo = $signerInfoList->item();

            if ($signerInfo->signerId() != $verifyingKey->getIdentifier())
                throw new PureCryptoException(PureCryptoErrorStatus::SIGNER_IS_ABSENT());

            if (!$cipher->verifySignerInfo($signerInfo, $verifyingKey->getPublicKey()))
                throw new PureCryptoException(PureCryptoErrorStatus::SIGNATURE_VERIFICATION_FAILED());

            return $this->concat($body1, $body2);

        } catch (\FoundationException $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $cms
     * @param VirgilPrivateKey $privateKey
     * @param VirgilPublicKeyCollection $publicKeys
     * @return string
     * @throws PureCryptoException
     */
    public function addRecipientsToCellKey(string $cms, VirgilPrivateKey $privateKey, VirgilPublicKeyCollection $publicKeys): string
    {
        try {
            $infoEditor = new MessageInfoEditor();
            $infoEditor->useRandom($this->crypto->getRng());

            $infoEditor->unpack($cms);
            $infoEditor->unlock($privateKey->getIdentifier(), $privateKey->getPrivateKey());

            foreach ($publicKeys->getAsArray() as $publicKey) {
                $infoEditor->addKeyRecipient($publicKey->getIdentifier(), $publicKey->getPublicKey());
            }

            return $infoEditor->pack();

        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $cms
     * @param VirgilPublicKeyCollection $publicKeys
     * @return string
     * @throws PureCryptoException
     */
    public function deleteRecipientsFromCellKey(string $cms, VirgilPublicKeyCollection $publicKeys): string
    {
        try {
            $infoEditor = new MessageInfoEditor();

            $infoEditor->useRandom($this->crypto->getRng());
            $infoEditor->unpack($cms);

            foreach ($publicKeys->getAsArray() as $publicKey) {
                $infoEditor->removeKeyRecipient($publicKey->getIdentifier());
            }

            return $infoEditor->pack();

        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $cms
     * @return array
     * @throws PureCryptoException
     */
    public function extractPublicKeysIdsFromCellKey(string $cms): array
    {
        $publicKeysIds = [];

        try {
            $messageInfoSerializer = new MessageInfoDerSerializer();
            $messageInfoSerializer->setupDefaults();

            $messageInfo = $messageInfoSerializer->deserialize($cms);
            $keyRecipientInfoList = $messageInfo->keyRecipientInfoList();

            while ($keyRecipientInfoList != null && $keyRecipientInfoList->hasItem()) {

                $keyRecipientInfo = $keyRecipientInfoList->item();
                $publicKeysIds[] = $keyRecipientInfo->recipientId();

                if ($keyRecipientInfoList->hasNext()) {
                    $keyRecipientInfoList = $keyRecipientInfoList->next();
                } else {
                    $keyRecipientInfoList = null;
                }
            }

            return $publicKeysIds;

        } catch (\FoundationException $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @return string
     * @throws \Virgil\Crypto\Exceptions\VirgilCryptoException
     */
    public function generateSymmetricOneTimeKey(): string
    {
        return $this->crypto->generateRandomData(self::DERIVED_SECRET_LENGTH);
    }

    /**
     * @param string $key
     * @return string
     */
    public function computeSymmetricKeyId(string $key): string
    {
        return $this->crypto->computeHash($key, HashAlgorithms::SHA512());
    }

    /**
     * @param string $plainText
     * @param string $ad
     * @param string $key
     * @return string
     * @throws PureCryptoException
     */
    public function encryptSymmetricWithOneTimeKey(string $plainText, string $ad, string $key): string
    {
        try {
            $aes256Gcm = new Aes256Gcm();
            $aes256Gcm->setKey(substr($key, 0, $aes256Gcm::KEY_LEN));
            $aes256Gcm->setNonce(substr($key, $aes256Gcm::KEY_LEN, $aes256Gcm::KEY_LEN + $aes256Gcm::NONCE_LEN));

            // [out, tag]
            $authEncryptAuthEncryptResult = $aes256Gcm->authEncrypt($plainText, $ad);

            return $this->concat($authEncryptAuthEncryptResult[0], $authEncryptAuthEncryptResult[1]);
        } catch (\FoundationException $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $cipherText
     * @param string $ad
     * @param string $key
     * @return string
     * @throws PureCryptoException
     */
    public function decryptSymmetricWithOneTimeKey(string $cipherText, string $ad, string $key): string
    {
        try {
            $aes256Gcm = new Aes256Gcm();
            $aes256Gcm->setKey(substr($key, 0, $aes256Gcm::KEY_LEN));
            $aes256Gcm->setNonce(substr($key, $aes256Gcm::KEY_LEN, $aes256Gcm::KEY_LEN + $aes256Gcm::NONCE_LEN));

            return $aes256Gcm->authDecrypt($cipherText, $ad, "");
        } catch (\FoundationException $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $plainText
     * @param string $ad
     * @param string $key
     * @return string
     * @throws PureCryptoException
     */
    public function encryptSymmetricWithNewNonce(string $plainText, string $ad, string $key)
    {
        try {
            return $this->pheCipher->authEncrypt($plainText, $ad, $key);
        } catch (\PheException $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $cipherText
     * @param string $ad
     * @param string $key
     * @return string
     * @throws PureCryptoException
     */
    public function decryptSymmetricWithNewNonce(string $cipherText, string $ad, string $key)
    {
        try {
            return $this->pheCipher->authDecrypt($cipherText, $ad, $key);
        } catch (\PheException $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $body1
     * @param string $body2
     * @return string
     */
    private function concat(string $body1, string $body2): string
    {
        return $body1 . $body2;
    }

    /**
     * @return VirgilKeyPair
     * @throws PureCryptoException
     */
    public function generateUserKey(): VirgilKeyPair
    {
        try {
            return $this->crypto->generateKeyPair(KeyPairType::ED25519());
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @return VirgilKeyPair
     * @throws PureCryptoException
     */
    public function generateRoleKey(): VirgilKeyPair
    {
        try {
            return $this->crypto->generateKeyPair(KeyPairType::ED25519());
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @return VirgilKeyPair
     * @throws PureCryptoException
     */
    public function generateCellKey(): VirgilKeyPair
    {
        try {
            return $this->crypto->generateKeyPair(KeyPairType::ED25519());
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $privateKey
     * @return VirgilKeyPair
     * @throws PureCryptoException
     */
    public function importPrivateKey(string $privateKey): VirgilKeyPair
    {
        try {
            return $this->crypto->importPrivateKey($privateKey);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $publicKey
     * @return VirgilPublicKey
     * @throws PureCryptoException
     */
    public function importPublicKey(string $publicKey): VirgilPublicKey
    {
        try {
            return $this->crypto->importPublicKey($publicKey);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param VirgilPublicKey $publicKey
     * @return string
     * @throws PureCryptoException
     */
    public function exportPublicKey(VirgilPublicKey $publicKey): string
    {
        try {
            return $this->crypto->exportPublicKey($publicKey);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param VirgilPrivateKey $privateKey
     * @return string
     * @throws PureCryptoException
     * @throws PureCryptoException
     */
    public function exportPrivateKey(VirgilPrivateKey $privateKey): string
    {
        try {
            return $this->crypto->exportPrivateKey($privateKey);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $plainText
     * @param VirgilPublicKey $encryptKey
     * @param VirgilPrivateKey $signingKey
     * @return string
     * @throws PureCryptoException
     */
    public function encryptForBackup(string $plainText, VirgilPublicKey $encryptKey, VirgilPrivateKey $signingKey):
    string
    {
        try {
            $recipients = new VirgilPublicKeyCollection($encryptKey);

            return $this->crypto->authEncrypt($plainText, $signingKey, $recipients);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $cipherText
     * @param VirgilPrivateKey $decryptKey
     * @param VirgilPublicKey $verifyKey
     * @return string
     * @throws PureCryptoException
     */
    public function decryptBackup(string $cipherText, VirgilPrivateKey $decryptKey, VirgilPublicKey $verifyKey): string
    {
        try {
            $recipients = new VirgilPublicKeyCollection($verifyKey);

            return $this->crypto->authDecrypt($cipherText, $decryptKey, $recipients);
        } catch (VerificationException | DecryptionException $exception) {
            throw new PureCryptoException($exception);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $plainText
     * @param VirgilPublicKeyCollection $publicKeys
     * @param VirgilPrivateKey $privateKey
     * @return string
     * @throws PureCryptoException
     */
    public function encryptData(string $plainText, VirgilPublicKeyCollection $publicKeys, VirgilPrivateKey $privateKey): string
    {
        try {
            $recipients = new VirgilPublicKeyCollection();

            foreach ($publicKeys->getAsArray() as $publicKey) {
                $recipients->addPublicKey($publicKey);
            }

            return $this->crypto->authEncrypt($plainText, $privateKey, $recipients);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $cipherText
     * @param VirgilPrivateKey $privateKey
     * @param VirgilPublicKey $publicKey
     * @return string
     * @throws PureCryptoException
     */
    public function decryptData(string $cipherText, VirgilPrivateKey $privateKey, VirgilPublicKey $publicKey): string
    {
        try {
            $recipients = new VirgilPublicKeyCollection($publicKey);

            return $this->crypto->authDecrypt($cipherText, $privateKey, $recipients);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $plainText
     * @param VirgilPublicKey $publicKey
     * @param VirgilPrivateKey $privateKey
     * @return string
     * @throws PureCryptoException
     */
    public function encryptRolePrivateKey(string $plainText, VirgilPublicKey $publicKey, VirgilPrivateKey $privateKey): string
    {
        try {
            $recipients = new VirgilPublicKeyCollection($publicKey);

            return $this->crypto->authEncrypt($plainText, $privateKey, $recipients);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $plainText
     * @param VirgilPrivateKey $privateKey
     * @param VirgilPublicKey $publicKey
     * @return string
     * @throws PureCryptoException
     */
    public function decryptRolePrivateKey(string $plainText, VirgilPrivateKey $privateKey, VirgilPublicKey $publicKey): string
    {
        try {
            $recipients = new VirgilPublicKeyCollection($publicKey);

            return $this->crypto->authDecrypt($plainText, $privateKey, $recipients);
        } catch (\Exception $exception) {
            throw new PureCryptoException($exception);
        }
    }

    /**
     * @param string $password
     * @return null|string
     */
    public function computePasswordHash(string $password)
    {
        return $this->crypto->computeHash($password, HashAlgorithms::SHA512());
    }
}