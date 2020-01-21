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

namespace Virgil\PureKit\Client;

use MyCLabs\Enum\Enum;

class AvailableRequests extends Enum
{
    private const ENROLL = "/enroll";
    private const VERIFY_PASSWORD = "/verify-password";
    private const INSERT_USER = "/user";
    private const UPDATE_USER = "/user/%s";
    private const GET_USER = "/user/%s";
    private const GET_USERS = "/get-users";
    private const DELETE_USER = "/user/%s";
    private const INSERT_CELL_KEY = "/cell-key";
    private const UPDATE_CELL_KEY = "/cell-key/%s/%s";
    private const GET_CELL_KEY = "/cell-key/%s/%s";
    private const DELETE_CELL_KEY = "/cell-key/%s/%s";
    private const INSERT_ROLE = "/roles";
    private const GET_ROLES = "/get-roles";
    private const INSERT_ROLE_ASSIGNMENTS = "/role-assignments";
    private const GET_ROLE_ASSIGNMENTS = "/get-role-assignments";
    private const GET_ROLE_ASSIGNMENT = "/get-role-assignment";
    private const DELETE_ROLE_ASSIGNMENTS = "/delete-role-assignments";
}