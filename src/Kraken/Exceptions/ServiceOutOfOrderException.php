<?php

namespace Kraken\Exceptions;

/**
 * Class ServiceOutOfOrderException
 * @package Kraken\Exceptions
 * @author Eric Pidoux
 * @version 1.0
 */
class ServiceOutOfOrderException extends \Exception {

    public function __construct($message=null, $code = 1, \Exception $previous = null) {


        parent::__construct("The current service isn't available or the limit of free service has been reached", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}