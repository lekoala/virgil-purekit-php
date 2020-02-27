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

class CellKey
{
    private $userId;
    private $dataId;
    private $cpk;
    private $encryptedCskCms;
    private $encryptedCskBody;

    /**
     * CellKey constructor.
     * @param string $userId
     * @param string $dataId
     * @param string $cpk
     * @param string $encryptedCskCms
     * @param string $encryptedCskBody
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function __construct(string $userId, string $dataId, string $cpk, string $encryptedCskCms, string $encryptedCskBody)
    {
        ValidateUtil::checkNullOrEmpty($userId, "userId");
        ValidateUtil::checkNullOrEmpty($dataId, "dataId");
        ValidateUtil::checkNullOrEmpty($cpk, "cpk");
        ValidateUtil::checkNullOrEmpty($encryptedCskCms, "encryptedCskCms");
        ValidateUtil::checkNullOrEmpty($encryptedCskBody, "encryptedCskBody");

        $this->userId = $userId;
        $this->dataId = $dataId;
        $this->cpk = $cpk;
        $this->encryptedCskCms = $encryptedCskCms;
        $this->encryptedCskBody = $encryptedCskBody;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getDataId(): string
    {
        return $this->dataId;
    }

    public function getCpk(): string
    {
        return $this->cpk;
    }

    public function getEncryptedCskCms(): string
    {
        return $this->encryptedCskCms;
    }

    public function getEncryptedCskBody(): string
    {
        return $this->encryptedCskBody;
    }
}