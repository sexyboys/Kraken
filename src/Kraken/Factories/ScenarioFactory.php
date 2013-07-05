<?php

namespace Kraken\Factories;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ScenarioFactory
 * @package Kraken\Factories
 * @author Eric Pidoux
 * @version 1.0
 */
class ScenarioFactory {

    /**
     * @var Instance of the Factory
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Constructor
     *
     */
    private function __construct() {

    }

    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new ScenarioFactory();
        }
        return self::$_instance;
    }


}