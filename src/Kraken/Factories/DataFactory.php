<?php

namespace Kraken\Factories;

use Doctrine\Common\Collections\ArrayCollection;
use Inflexible\Inflexible;
use Kraken\Entities\Data\DataArticle;
use Kraken\Entities\Data\DataDate;
use Kraken\Entities\Data\DataInteger;
use Kraken\Entities\Data\DataList;
use Kraken\Entities\Data\DataString;

/**
 * Class DataFactory
 * @package Kraken\Factories
 * @author Eric Pidoux
 * @version 1.0
 */
class DataFactory {

    const NONE=-2;

    const SAME=-1;

    const TYPE_ARTICLE=0;

    const TYPE_DATE=1;

    const TYPE_INTEGER=2;

    const TYPE_LIST=3;

    const TYPE_STRING=4;

    const TYPE_LIST_STRING=5;

    const TYPE_LIST_ARTICLE=6;

    /**
     * @var Instance of the Factory
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * ArrayCollection of task types
     */
    private $types;

    /**
     * Constructor
     *
     */
    private function __construct() {

    }

    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new DataFactory();
        }
        return self::$_instance;
    }

    /**
     * Load an instance
     * @param $index
     */
    public function getDataInstance($index)
    {
        $instance = null;
        if($index == self::TYPE_ARTICLE)
        {
            $instance = new DataArticle();
        }
        else if($index == self::TYPE_DATE)
        {
            $instance = new DataDate();
        }
        else if($index == self::TYPE_INTEGER)
        {
            $instance = new DataInteger();
        }
        else if($index == self::TYPE_LIST)
        {
            $instance = new DataList();
        }
        else if($index == self::TYPE_STRING)
        {
            $instance = new DataString();
        }
        else if($index == self::TYPE_LIST_STRING)
        {
            $instance = new DataList();
            $r = new DataString();
            $array = new ArrayCollection();
            $array->add($r);
            $instance->setContent($array);
        }
        else if($index == self::TYPE_LIST_ARTICLE)
        {
            $instance = new DataString();
            $r = new DataArticle();
            $array = new ArrayCollection();
            $array->add($r);
            $instance->setContent($array);
        }

        return $instance;
    }


}