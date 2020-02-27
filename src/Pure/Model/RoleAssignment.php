<?php
/**
 * Copyright (C) 2015-2020 Virgil Security Inc.
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

namespace Virgil\PureKit\Pure\Model;

use Virgil\PureKit\Pure\Util\ValidateUtil;

class RoleAssignment
{
    private $roleName;
    private $userId;
    private $publicKeyId;
    private $encryptedRsk;

    public function __construct(string $roleName, string $userId, string $publicKeyId, string $encryptedRsk)
    {
        ValidateUtil::checkNullOrEmpty($roleName, "roleName");
        ValidateUtil::checkNullOrEmpty($userId, "userId");
        ValidateUtil::checkNullOrEmpty($publicKeyId, "publicKeyId");
        ValidateUtil::checkNullOrEmpty($encryptedRsk, "encryptedRsk");

        $this->roleName = $roleName;
        $this->userId = $userId;
        $this->publicKeyId = $publicKeyId;
        $this->encryptedRsk = $encryptedRsk;
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPublicKeyId(): string
    {
        return $this->publicKeyId;
    }

    public function getEncryptedRsk(): string
    {
        return $this->encryptedRsk;
    }
}