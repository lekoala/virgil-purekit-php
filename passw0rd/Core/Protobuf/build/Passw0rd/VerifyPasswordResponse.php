<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: passw0rd.proto

namespace Passw0rd;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>passw0rd.VerifyPasswordResponse</code>
 */
class VerifyPasswordResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>bytes response = 1;</code>
     */
    private $response = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $response
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Passw0Rd::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>bytes response = 1;</code>
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Generated from protobuf field <code>bytes response = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setResponse($var)
    {
        GPBUtil::checkString($var, False);
        $this->response = $var;

        return $this;
    }

}

