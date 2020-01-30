<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: purekitV3_grant.proto

namespace PurekitV3Grant;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>purekitV3Grant.EncryptedGrantHeader</code>
 */
class EncryptedGrantHeader extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 creation_date = 1;</code>
     */
    private $creation_date = 0;
    /**
     * Generated from protobuf field <code>string user_id = 2;</code>
     */
    private $user_id = '';
    /**
     * Generated from protobuf field <code>string session_id = 3;</code>
     */
    private $session_id = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $creation_date
     *     @type string $user_id
     *     @type string $session_id
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\PurekitV3Grant::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>uint32 creation_date = 1;</code>
     * @return int
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Generated from protobuf field <code>uint32 creation_date = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setCreationDate($var)
    {
        GPBUtil::checkUint32($var);
        $this->creation_date = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string user_id = 2;</code>
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Generated from protobuf field <code>string user_id = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setUserId($var)
    {
        GPBUtil::checkString($var, True);
        $this->user_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string session_id = 3;</code>
     * @return string
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * Generated from protobuf field <code>string session_id = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setSessionId($var)
    {
        GPBUtil::checkString($var, True);
        $this->session_id = $var;

        return $this;
    }

}
