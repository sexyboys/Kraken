<?php

namespace Kraken\Exceptions;

/**
 * Class ServiceNoKeyException
 * @package Kraken\Exceptions
 * @author Eric Pidoux
 * @version 1.0
 */
class ServiceNoKeyException extends \Exception {

    public function __construct($message=null, $code = 1, \Exception $previous = null) {


        parent::__construct("The current service require a key and you haven't provide it", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}