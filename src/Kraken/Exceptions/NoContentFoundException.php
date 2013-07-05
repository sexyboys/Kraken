<?php

namespace Kraken\Exceptions;

/**
 * Class NoContentFoundException
 * @package Kraken\Exceptions
 * @author Eric Pidoux
 * @version 1.0
 */
class NoContentFoundException extends \Exception {

    public function __construct($message=null, $code = 1, \Exception $previous = null) {


        parent::__construct("No content was found", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}