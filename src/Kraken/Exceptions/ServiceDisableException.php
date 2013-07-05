<?php

namespace Kraken\Exceptions;

/**
 * Class ServiceDisableException
 * @package Kraken\Exceptions
 * @author Eric Pidoux
 * @version 1.0
 */
class ServiceDisableException extends \Exception {

    public function __construct($message=null, $code = 1, \Exception $previous = null) {


        parent::__construct("The current service isn't available", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}