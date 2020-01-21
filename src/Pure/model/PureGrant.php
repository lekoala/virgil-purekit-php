<?php
/**
 * Copyright (C) 2015-2019 Virgil Security Inc.
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

namespace Virgil\PureKit\Pure\model;

use Virgil\CryptoImpl\Core\VirgilKeyPair;
use Virgil\PureKit\Pure\Helper\Date;
use Virgil\PureKit\Pure\Util\ValidateUtil;

class PureGrant
{
    private $ukp;
    private $userId;
    private $sessionId;
    private $creationDate;

    public function __construct(VirgilKeyPair $ukp, string $userId, string $sessionId, Date $creationDate)
    {
        ValidateUtil::checkNull($ukp, "ukp");
        ValidateUtil::checkNullOrEmpty($userId, "userId");
        ValidateUtil::checkNull($creationDate, "creationDate");

        $this->ukp = $ukp;
        $this->userId = $userId;
        $this->sessionId = $sessionId;
        $this->creationDate = $creationDate;
    }

    public function getUpk(): VirgilKeyPair
    {
        return $this->ukp;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getCreationDate(): Date
    {
        return $this->creationDate;
    }
}