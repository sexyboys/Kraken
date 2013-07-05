<?php

namespace Kraken\Exceptions;

/**
 * Class XSLTParseErrorException
 * @package Kraken\Exceptions
 * @author Eric Pidoux
 * @version 1.0
 */
class XSLTParseErrorException extends \Exception {


    public function __construct($msg=null,$xmlContent=null,$xslContent=null) {
        parent::__construct($msg);

    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}