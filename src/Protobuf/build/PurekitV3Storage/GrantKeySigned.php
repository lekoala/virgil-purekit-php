<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: purekitV3_storage.proto

namespace PurekitV3Storage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>purekitV3Storage.GrantKeySigned</code>
 */
class GrantKeySigned extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 version = 1;</code>
     */
    private $version = 0;
    /**
     * Generated from protobuf field <code>string user_id = 2;</code>
     */
    private $user_id = '';
    /**
     * Generated from protobuf field <code>bytes key_id = 3;</code>
     */
    private $key_id = '';
    /**
     * Generated from protobuf field <code>bytes encrypted_grant_key_blob = 4;</code>
     */
    private $encrypted_grant_key_blob = '';
    /**
     * Generated from protobuf field <code>uint64 creation_date = 5;</code>
     */
    private $creation_date = 0;
    /**
     * Generated from protobuf field <code>uint64 expiration_date = 6;</code>
     */
    private $expiration_date = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $version
     *     @type string $user_id
     *     @type string $key_id
     *     @type string $encrypted_grant_key_blob
     *     @type int|string $creation_date
     *     @type int|string $expiration_date
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\PurekitV3Storage::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>uint32 version = 1;</code>
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Generated from protobuf field <code>uint32 version = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setVersion($var)
    {
        GPBUtil::checkUint32($var);
        $this->version = $var;

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
     * Generated from protobuf field <code>bytes key_id = 3;</code>
     * @return string
     */
    public function getKeyId()
    {
        return $this->key_id;
    }

    /**
     * Generated from protobuf field <code>bytes key_id = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setKeyId($var)
    {
        GPBUtil::checkString($var, False);
        $this->key_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_grant_key_blob = 4;</code>
     * @return string
     */
    public function getEncryptedGrantKeyBlob()
    {
        return $this->encrypted_grant_key_blob;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_grant_key_blob = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setEncryptedGrantKeyBlob($var)
    {
        GPBUtil::checkString($var, False);
        $this->encrypted_grant_key_blob = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 creation_date = 5;</code>
     * @return int|string
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Generated from protobuf field <code>uint64 creation_date = 5;</code>
     * @param int|string $var
     * @return $this
     */
    public function setCreationDate($var)
    {
        GPBUtil::checkUint64($var);
        $this->creation_date = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 expiration_date = 6;</code>
     * @return int|string
     */
    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

    /**
     * Generated from protobuf field <code>uint64 expiration_date = 6;</code>
     * @param int|string $var
     * @return $this
     */
    public function setExpirationDate($var)
    {
        GPBUtil::checkUint64($var);
        $this->expiration_date = $var;

        return $this;
    }

}

