<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: purekitV3_storage.proto

namespace PurekitV3Storage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>purekitV3Storage.RoleAssignments</code>
 */
class RoleAssignments extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .purekitV3Storage.RoleAssignment role_assignments = 1;</code>
     */
    private $role_assignments;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \PurekitV3Storage\RoleAssignment[]|\Google\Protobuf\Internal\RepeatedField $role_assignments
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\PurekitV3Storage::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .purekitV3Storage.RoleAssignment role_assignments = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getRoleAssignments()
    {
        return $this->role_assignments;
    }

    /**
     * Generated from protobuf field <code>repeated .purekitV3Storage.RoleAssignment role_assignments = 1;</code>
     * @param \PurekitV3Storage\RoleAssignment[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setRoleAssignments($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \PurekitV3Storage\RoleAssignment::class);
        $this->role_assignments = $arr;

        return $this;
    }

}

