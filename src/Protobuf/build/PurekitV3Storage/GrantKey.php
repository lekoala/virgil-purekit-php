<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: purekitV3_storage.proto

namespace PurekitV3Storage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>purekitV3Storage.GrantKey</code>
 */
class GrantKey extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 version = 1;</code>
     */
    private $version = 0;
    /**
     * Generated from protobuf field <code>bytes grant_key_signed = 2;</code>
     */
    private $grant_key_signed = '';
    /**
     * Generated from protobuf field <code>bytes signature = 3;</code>
     */
    private $signature = '';
    /**
     * Generated from protobuf field <code>uint32 record_version = 4;</code>
     */
    private $record_version = 0;
    /**
     * Generated from protobuf field <code>bytes encrypted_grant_key_wrap = 5;</code>
     */
    private $encrypted_grant_key_wrap = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $version
     *     @type string $grant_key_signed
     *     @type string $signature
     *     @type int $record_version
     *     @type string $encrypted_grant_key_wrap
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
     * Generated from protobuf field <code>bytes grant_key_signed = 2;</code>
     * @return string
     */
    public function getGrantKeySigned()
    {
        return $this->grant_key_signed;
    }

    /**
     * Generated from protobuf field <code>bytes grant_key_signed = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setGrantKeySigned($var)
    {
        GPBUtil::checkString($var, False);
        $this->grant_key_signed = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes signature = 3;</code>
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Generated from protobuf field <code>bytes signature = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setSignature($var)
    {
        GPBUtil::checkString($var, False);
        $this->signature = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 record_version = 4;</code>
     * @return int
     */
    public function getRecordVersion()
    {
        return $this->record_version;
    }

    /**
     * Generated from protobuf field <code>uint32 record_version = 4;</code>
     * @param int $var
     * @return $this
     */
    public function setRecordVersion($var)
    {
        GPBUtil::checkUint32($var);
        $this->record_version = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_grant_key_wrap = 5;</code>
     * @return string
     */
    public function getEncryptedGrantKeyWrap()
    {
        return $this->encrypted_grant_key_wrap;
    }

    /**
     * Generated from protobuf field <code>bytes encrypted_grant_key_wrap = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setEncryptedGrantKeyWrap($var)
    {
        GPBUtil::checkString($var, False);
        $this->encrypted_grant_key_wrap = $var;

        return $this;
    }

}

