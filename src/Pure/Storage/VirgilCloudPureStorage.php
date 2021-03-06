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

namespace Virgil\PureKit\Pure\Storage;

use PurekitV3Storage\RoleAssignments as ProtoRoleAssignments;
use Virgil\PureKit\Http\HttpPureClient;
use Virgil\PureKit\Http\Request\Pure\DeleteCellKeyRequest;
use Virgil\PureKit\Http\Request\Pure\DeleteGrantKeyRequest;
use Virgil\PureKit\Http\Request\Pure\DeleteRoleAssignmentsRequest;
use Virgil\PureKit\Http\Request\Pure\DeleteRoleRequest;
use Virgil\PureKit\Http\Request\Pure\DeleteUserRequest;
use Virgil\PureKit\Http\Request\Pure\GetRoleAssignmentRequest;
use Virgil\PureKit\Http\Request\Pure\GetRoleAssignmentsRequest;
use Virgil\PureKit\Http\Request\Pure\InsertCellKeyRequest;
use Virgil\PureKit\Http\Request\Pure\GetRolesRequest;
use Virgil\PureKit\Http\Request\Pure\GetUsersRequest;
use Virgil\PureKit\Http\Request\Pure\GetCellKeyRequest;
use Virgil\PureKit\Http\Request\Pure\GetGrantKeyRequest;
use Virgil\PureKit\Http\Request\Pure\GetUserRequest;
use Virgil\PureKit\Http\Request\Pure\InsertGrantKeyRequest;
use Virgil\PureKit\Http\Request\Pure\InsertRoleAssignmentsRequest;
use Virgil\PureKit\Http\Request\Pure\InsertRoleRequest;
use Virgil\PureKit\Http\Request\Pure\InsertUserRequest;
use Virgil\PureKit\Http\Request\Pure\UpdateCellKeyRequest;
use Virgil\PureKit\Http\Request\Pure\UpdateUserRequest;
use Virgil\PureKit\Pure\Collection\GrantKeyCollection;
use Virgil\PureKit\Pure\Collection\RoleAssignmentCollection;
use Virgil\PureKit\Pure\Collection\RoleCollection;
use Virgil\PureKit\Pure\Collection\UserRecordCollection;
use Virgil\PureKit\Pure\Exception\ErrorStatus\PureStorageGenericErrorStatus;
use Virgil\PureKit\Pure\Exception\ProtocolException;
use Virgil\PureKit\Pure\Exception\ProtocolHttpException;
use Virgil\PureKit\Pure\Exception\PureStorageCellKEyAlreadyExistsException;
use Virgil\PureKit\Pure\Exception\PureStorageCellKeyNotFoundException;
use Virgil\PureKit\Pure\Exception\PureStorageGenericException;
use Virgil\PureKit\Pure\Exception\ErrorStatus\ServiceErrorCode;
use Virgil\PureKit\Pure\Exception\PureStorageGrantKeyNotFoundException;
use Virgil\PureKit\Pure\Exception\PureStorageRoleAssignmentNotFoundException;
use Virgil\PureKit\Pure\Exception\PureStorageUserNotFoundException;
use Virgil\PureKit\Pure\Exception\UnsupportedOperationException;
use Virgil\PureKit\Pure\Exception\VirgilCloudStorageException;
use Virgil\PureKit\Pure\Model\CellKey;
use Virgil\PureKit\Pure\Model\GrantKey;
use Virgil\PureKit\Pure\Model\Role;
use Virgil\PureKit\Pure\Model\RoleAssignment;
use Virgil\PureKit\Pure\Model\UserRecord;
use Virgil\PureKit\Pure\PureModelSerializer;
use Virgil\PureKit\Pure\PureModelSerializerDependent;
use Virgil\PureKit\Pure\Util\ValidationUtils;

/**
 * Class VirgilCloudPureStorage
 * @package Virgil\PureKit\Pure\Storage
 */
class VirgilCloudPureStorage implements PureStorage, PureModelSerializerDependent
{
    /**
     * @var
     */
    private $pureModelSerializer;
    /**
     * @var HttpPureClient
     */
    private $client;

    /**
     * VirgilCloudPureStorage constructor.
     * @param HttpPureClient $client
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function __construct(HttpPureClient $client)
    {
        ValidationUtils::checkNull($client, "client");

        $this->client = $client;
    }

    /**
     * @return PureModelSerializer
     */
    public function getPureModelSerializer(): PureModelSerializer
    {
        return $this->pureModelSerializer;
    }

    /**
     * @param PureModelSerializer $pureModelSerializer
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function setPureModelSerializer(PureModelSerializer $pureModelSerializer): void
    {
        ValidationUtils::checkNull($pureModelSerializer, "pureModelSerializer");
        $this->pureModelSerializer = $pureModelSerializer;
    }

    /**
     * @param UserRecord $userRecord
     * @throws ProtocolException
     * @throws PureStorageGenericException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function insertUser(UserRecord $userRecord): void
    {
        ValidationUtils::checkNull($userRecord, "userRecord");
        $this->_sendUser($userRecord, true);
    }

    /**
     * @param UserRecord $userRecord
     * @throws ProtocolException
     * @throws PureStorageGenericException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function updateUser(UserRecord $userRecord): void
    {
        ValidationUtils::checkNull($userRecord, "userRecord");
        $this->_sendUser($userRecord, false);
    }

    /**
     * @param UserRecordCollection $userRecords
     * @param int $previousPheVersion
     * @throws UnsupportedOperationException
     */
    public function updateUsers(UserRecordCollection $userRecords, int $previousPheVersion): void
    {
        throw new UnsupportedOperationException("This method always throws UnsupportedOperationException, as in case of using Virgil Cloud storage, rotation happens on the Virgil side");
    }

    /**
     * @param string $userId
     * @return UserRecord
     * @throws PureStorageGenericException
     * @throws PureStorageUserNotFoundException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function selectUser(string $userId): UserRecord
    {
        ValidationUtils::checkNullOrEmpty($userId, "userId");

        try {
            $request = new GetUserRequest($userId);
            $protobufRecord = $this->client->getUser($request);
        } catch (ProtocolException $exception) {
            if ($exception->getCode() == ServiceErrorCode::USER_NOT_FOUND()->getCode()) {
                throw new PureStorageUserNotFoundException([$userId]);
            }

            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }

        $userRecord = $this->pureModelSerializer->parseUserRecord($protobufRecord);

        if ($userRecord->getUserId() != $userId)
            throw new PureStorageGenericException(PureStorageGenericErrorStatus::USER_ID_MISMATCH());


        return $userRecord;
    }

    /**
     * @param array $userIds
     * @return UserRecordCollection
     * @throws PureStorageGenericException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function selectUsers(array $userIds): UserRecordCollection
    {
        ValidationUtils::checkNull($userIds, "userIds");

        $userRecords = new UserRecordCollection();

        if (empty($userIds))
            return $userRecords;

        $idsSet = $userIds;

        try {
            $request = new GetUsersRequest($userIds);
            $protoRecords = $this->client->getUsers($request);

        } catch (ProtocolException $exception) {
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }

        if (count($protoRecords->getUserRecords()) != count($userIds)) {
            throw new PureStorageGenericException( PureStorageGenericErrorStatus::USER_COUNT_MISMATCH());
        }

        foreach ($protoRecords->getUserRecords() as $protobufRecord) {
            $userRecord = $this->pureModelSerializer->parseUserRecord($protobufRecord);

            if (!in_array($userRecord->getUserId(), $idsSet))
                throw new PureStorageGenericException(PureStorageGenericErrorStatus::USER_ID_MISMATCH());

            if (($key = array_search($userRecord->getUserId(), $idsSet)) !== false) {
                unset($idsSet[$key]);
                $idsSet = array_values($idsSet);
            }

            $userRecords->add($userRecord);
        }

        return $userRecords;
    }

    /**
     * @param int $recordVersion
     * @return UserRecordCollection
     * @throws UnsupportedOperationException
     */
    public function selectUsers_(int $recordVersion): UserRecordCollection
    {
        throw new UnsupportedOperationException("This method always throws UnsupportedOperationException, as in case of using Virgil Cloud storage, rotation happens on the Virgil side");
    }

    /**
     * @param string $userId
     * @param bool $cascade
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function deleteUser(string $userId, bool $cascade): void
    {
        ValidationUtils::checkNullOrEmpty($userId, "userId");

        try {
            $request = new DeleteUserRequest($userId, $cascade);
            $this->client->deleteUser($request);
        } catch (ProtocolException $exception) {
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }
    }

    /**
     * @param string $userId
     * @param string $dataId
     * @return CellKey
     * @throws PureStorageCellKeyNotFoundException
     * @throws PureStorageGenericException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function selectCellKey(string $userId, string $dataId): CellKey
    {
        ValidationUtils::checkNullOrEmpty($userId, "userId");
        ValidationUtils::checkNullOrEmpty($dataId, "dataId");

        try {
            $request = new GetCellKeyRequest($userId, $dataId);
            $protobufRecord = $this->client->getCellKey($request);
        }
        catch (ProtocolException $exception) {
            if ($exception->getCode() == ServiceErrorCode::CELL_KEY_NOT_FOUND()->getCode()) {
                throw new PureStorageCellKeyNotFoundException();
            }
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }

        $cellKey = $this->pureModelSerializer->parseCellKey($protobufRecord);


        if (($userId != $cellKey->getUserId()) || $dataId != $cellKey->getDataId()) {
            throw new PureStorageGenericException(PureStorageGenericErrorStatus::CELL_KEY_ID_MISMATCH());
        }

        return $cellKey;
    }

    /**
     * @param CellKey $cellKey
     * @throws PureStorageCellKEyAlreadyExistsException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function insertCellKey(CellKey $cellKey): void
    {
        ValidationUtils::checkNull($cellKey, "cellKey");

        $this->insertKey($cellKey, true);
    }

    /**
     * @param CellKey $cellKey
     * @throws PureStorageCellKEyAlreadyExistsException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function updateCellKey(CellKey $cellKey): void
    {
        ValidationUtils::checkNull($cellKey, "cellKey");

        $this->insertKey($cellKey, false);
    }

    /**
     * @param string $userId
     * @param string $dataId
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function deleteCellKey(string $userId, string $dataId): void
    {
        ValidationUtils::checkNullOrEmpty($userId, "userId");
        ValidationUtils::checkNullOrEmpty($dataId, "dataId");

        try {
            $request = new DeleteCellKeyRequest($userId, $dataId);

            $this->client->deleteCellKey($request);
        } catch (ProtocolException $exception) {
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }
    }

    /**
     * @param Role $role
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function insertRole(Role $role): void
    {
        ValidationUtils::checkNull($role, "role");

        $protobufRecord = $this->pureModelSerializer->serializeRole($role);
        $request = new InsertRoleRequest($protobufRecord);

        try {
            $this->client->insertRole($request);
        } catch (ProtocolException $exception) {
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }
    }

    /**
     * @param array $roleNames
     * @return RoleCollection
     * @throws PureStorageGenericException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function selectRoles(array $roleNames): RoleCollection
    {
        ValidationUtils::checkNull($roleNames, "roleNames");

        $roles = new RoleCollection();
        $namesSet = $roleNames;

        if (empty($roleNames))
            return $roles;

        try {
            $request = new GetRolesRequest($roleNames);
            $protoRecords = $this->client->getRoles($request);
        } catch (ProtocolException $exception) {
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }

        if (count($protoRecords->getRoles()) != count($roleNames)) {
            throw new PureStorageGenericException(PureStorageGenericErrorStatus::DUPLICATE_ROLE_NAME());
        }

        foreach ($protoRecords->getRoles() as $protobufRecord) {
            $role = $this->pureModelSerializer->parseRole($protobufRecord);

            if (!in_array($role->getRoleName(), $namesSet))
                throw new PureStorageGenericException(PureStorageGenericErrorStatus::ROLE_NAME_MISMATCH());

            if (($key = array_search($role->getRoleName(), $namesSet)) !== false) {
                unset($namesSet[$key]);
                $namesSet = array_values($namesSet);
            }
            $roles->add($role);
        }

        return $roles;
    }

    /**
     * @param string $roleName
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function deleteRole(string $roleName): void
    {
        ValidationUtils::checkNullOrEmpty($roleName, "roleName");

        try {
            $request = new DeleteRoleRequest($roleName);

            $this->client->deleteRole($request);
        }   catch (ProtocolException $exception) {
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }
    }

    /**
     * @param RoleAssignmentCollection $roleAssignments
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function insertRoleAssignments(RoleAssignmentCollection $roleAssignments): void
    {
        ValidationUtils::checkNull($roleAssignments, "roleAssignments");

        if (empty($roleAssignments->getAsArray())) {
            return;
        }

        $protobufBuilder = new ProtoRoleAssignments();
        $ra = [];

        if (!empty($roleAssignments->getAsArray())) {
            foreach ($roleAssignments->getAsArray() as $roleAssignment) {
                $ra[] = $this->pureModelSerializer->serializeRoleAssignment($roleAssignment);
            }
        }

        $protobufBuilder->setRoleAssignments($ra);
        $protobufRecord = $protobufBuilder;

        $request = new InsertRoleAssignmentsRequest($protobufRecord);

        try {
            $this->client->insertRoleAssignments($request);
        }
        catch (ProtocolException $exception) {
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }
    }

    /**
     * @param string $userId
     * @return RoleAssignmentCollection
     * @throws PureStorageGenericException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function selectRoleAssignments(string $userId): RoleAssignmentCollection
    {
        ValidationUtils::checkNullOrEmpty($userId, "userId");

        $roleAssignments = new RoleAssignmentCollection();
        $request = new GetRoleAssignmentsRequest($userId);
        $protoRecords = null;

        try {
            $protoRecords = $this->client->getRoleAssignments($request);
        }
        catch (ProtocolException $exception) {
            throw new VirgilCloudStorageException($exception);
        } catch (ProtocolHttpException $exception) {
            throw new VirgilCloudStorageException($exception);
        }

        foreach ($protoRecords->getRoleAssignments() as $protobufRecord) {
            $roleAssignment = $this->pureModelSerializer->parseRoleAssignment($protobufRecord);

            if ($roleAssignment->getUserId() != $userId)
                throw new PureStorageGenericException(PureStorageGenericErrorStatus::USER_ID_MISMATCH());

            $roleAssignments->add($roleAssignment);
        }

        return $roleAssignments;
    }

    /**
     * @param string $roleName
     * @param string $userId
     * @return RoleAssignment
     * @throws PureStorageRoleAssignmentNotFoundException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function selectRoleAssignment(string $roleName, string $userId): RoleAssignment
    {
        ValidationUtils::checkNullOrEmpty($roleName, "roleName");
        ValidationUtils::checkNullOrEmpty($userId, "userId");

        $request = new GetRoleAssignmentRequest($roleName, $userId);

        $protobufRecord = null;

        try {
            $protobufRecord = $this->client->getRoleAssignment($request);
        } catch (ProtocolException $e) {

            if ($e->getErrorCode() == ServiceErrorCode::ROLE_ASSIGNMENT_NOT_FOUND()->getCode()) {
                throw new PureStorageRoleAssignmentNotFoundException($userId, $roleName);
            }

            throw new VirgilCloudStorageException($e);
        } catch (ProtocolHttpException $e) {
            throw new VirgilCloudStorageException($e);
        }

        return $this->pureModelSerializer->parseRoleAssignment($protobufRecord);
    }

    /**
     * @param string $roleName
     * @param array $userIds
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function deleteRoleAssignments(string $roleName, array $userIds): void
    {
        ValidationUtils::checkNullOrEmpty($roleName, "roleName");
        ValidationUtils::checkNull($userIds, "userIds");

        if (empty($userIds))
            return;

        $request = new DeleteRoleAssignmentsRequest($roleName, $userIds);

        try {
            $this->client->deleteRoleAssignments($request);
        } catch (ProtocolException $e) {
            throw new VirgilCloudStorageException($e);
        } catch (ProtocolHttpException $e) {
            throw new VirgilCloudStorageException($e);
        }
    }

    /**
     * @param GrantKey $grantKey
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function insertGrantKey(GrantKey $grantKey): void
    {
        ValidationUtils::checkNull($grantKey, "grantKey");

        $protobufRecord = $this->pureModelSerializer->serializeGrantKey($grantKey);

        $request = new InsertGrantKeyRequest($protobufRecord);

        try {
            $this->client->insertGrantKey($request);
        } catch (ProtocolException | ProtocolHttpException $e) {
            throw new VirgilCloudStorageException($e);
        }
    }

    /**
     * @param string $userId
     * @param string $keyId
     * @return GrantKey
     * @throws PureStorageGenericException
     * @throws PureStorageGrantKeyNotFoundException
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function selectGrantKey(string $userId, string $keyId): GrantKey
    {
        ValidationUtils::checkNullOrEmpty($userId, "userId");
        ValidationUtils::checkNullOrEmpty($keyId, "keyId");

        $request = new GetGrantKeyRequest($userId, $keyId);

        try {
            $protobufRecord = $this->client->getGrantKey($request);
        }
        catch (ProtocolException $e) {
            if ($e->getCode() == ServiceErrorCode::GRANT_KEY_NOT_FOUND()->getCode())
                throw new PureStorageGrantKeyNotFoundException($userId, $keyId);

            throw new VirgilCloudStorageException($e);
        } catch (ProtocolHttpException $e) {
            throw new VirgilCloudStorageException($e);
        }

        $grantKey = $this->pureModelSerializer->parseGrantKey($protobufRecord);

        if ($grantKey->getUserId() != $userId) {
            throw new PureStorageGenericException(PureStorageGenericErrorStatus::USER_ID_MISMATCH());
        }
        if ($grantKey->getKeyId() != $keyId) {
            throw new PureStorageGenericException(PureStorageGenericErrorStatus::KEY_ID_MISMATCH());
        }

        return $grantKey;
    }

    /**
     * @param int $recordVersion
     * @return GrantKeyCollection
     * @throws UnsupportedOperationException
     */
    public function selectGrantKeys(int $recordVersion): GrantKeyCollection
    {
        throw new UnsupportedOperationException(
            "This method always throws UnsupportedOperationException, as in case of using Virgil Cloud storage, rotation happens on the Virgil side."
        );
    }

    /**
     * @param GrantKeyCollection $grantKeys
     * @throws UnsupportedOperationException
     */
    public function updateGrantKeys(GrantKeyCollection $grantKeys): void
    {
        throw new UnsupportedOperationException(
            "This method always throws UnsupportedOperationException, as in case of using Virgil Cloud storage, rotation happens on the Virgil side."
        );
    }

    /**
     * @param string $userId
     * @param string $keyId
     * @throws VirgilCloudStorageException
     * @throws \Virgil\PureKit\Pure\Exception\EmptyArgumentException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    public function deleteGrantKey(string $userId, string $keyId): void
    {
        ValidationUtils::checkNullOrEmpty($userId, "userId");
        ValidationUtils::checkNullOrEmpty($keyId, "keyId");

        $r = new DeleteGrantKeyRequest($userId, $keyId);

        try {
            $this->client->deleteGrantKey($r);
        } catch (ProtocolException | ProtocolHttpException $e) {
            throw new VirgilCloudStorageException($e);
        }
    }

    /**
     * @param UserRecord $userRecord
     * @param bool $isInsert
     * @throws ProtocolException
     * @throws PureStorageGenericException
     * @throws \Virgil\Crypto\Exceptions\VirgilCryptoException
     * @throws \Virgil\PureKit\Pure\Exception\IllegalStateException
     * @throws \Virgil\PureKit\Pure\Exception\NullArgumentException
     */
    private function _sendUser(UserRecord $userRecord, bool $isInsert): void
    {
        $protobufRecord = $this->getPureModelSerializer()->serializeUserRecord($userRecord);

        if ($isInsert) {

            $request = new InsertUserRequest($protobufRecord);
            $this->client->insertUser($request);
        } else {

            $request = new UpdateUserRequest($protobufRecord, $userRecord->getUserId());
            $this->client->updateUser($request);
        }
    }

    /**
     * @param CellKey $cellKey
     * @param bool $isInsert
     * @throws PureStorageCellKEyAlreadyExistsException
     * @throws VirgilCloudStorageException
     */
    private function insertKey(CellKey $cellKey, bool $isInsert): void
    {
        $protobufRecord = $this->pureModelSerializer->serializeCellKey($cellKey);

        try {
            if ($isInsert) {
                try {
                    $request = new InsertCellKeyRequest($protobufRecord);
                    $this->client->insertCellKey($request);
                } catch (ProtocolException $e) {
                    if ($e->getErrorCode() == ServiceErrorCode::CELL_KEY_ALREADY_EXISTS()->getCode()) {
                        throw new PureStorageCellKeyAlreadyExistsException();
                    }
                    throw $e;
                }
            } else {
                $request = new UpdateCellKeyRequest($cellKey->getUserId(),
                    $cellKey->getDataId(), $protobufRecord);
                $this->client->updateCellKey($request);
            }
        } catch (ProtocolException $e) {
            throw new VirgilCloudStorageException($e);
        } catch (ProtocolHttpException $e) {
            throw new VirgilCloudStorageException($e);
        }
    }
}