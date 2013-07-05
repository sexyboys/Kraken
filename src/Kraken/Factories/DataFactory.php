<?php

namespace Kraken\Factories;

use Doctrine\Common\Collections\ArrayCollection;
use Inflexible\Inflexible;
use Kraken\UserBundle\Entity\DataArticle;
use Kraken\UserBundle\Entity\DataDate;
use Kraken\UserBundle\Entity\DataInteger;
use Kraken\UserBundle\Entity\DataList;
use Kraken\UserBundle\Entity\DataString;

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
     * Generate general array of all name of types
     * @return array
     */
    public function getDatanamesArray()
    {

        $general_array[DataFactory::NONE] = 'DataNone';
        $general_array[DataFactory::SAME] = 'DataSame';
        $general_array[DataFactory::TYPE_ARTICLE] = 'DataArticle';
        $general_array[DataFactory::TYPE_DATE] = 'DataDate';
        $general_array[DataFactory::TYPE_INTEGER] = 'DataInteger';
        $general_array[DataFactory::TYPE_LIST] = 'DataList';
        $general_array[DataFactory::TYPE_STRING] = 'DataString';
        $general_array[DataFactory::TYPE_LIST_STRING] = 'DataListString';
        $general_array[DataFactory::TYPE_LIST_ARTICLE] ='DataListArticle';

        return $general_array;
    }

    /**
     * Retrieve name of given index
     * @param $index Integer
     * @return $name
     */
    public function getName($index)
    {
        $array = $this->getDatanamesArray();
        return $array[$index];
    }

    /**
     * Retrieve index of given name
     * @param $name String
     * @return $index
     */
    public function getIndex($name)
    {
        $result = null;
        $array = $this->getDatanamesArray();
        foreach($array as $key=>$row)
        {
            if($result == null && $row==$name) $result = $key;
        }
        return $result;

    }

    /**
     * Get the Data name of the given data instance
     * @param $data Data the data instance
     * @return the data name
     */
    public function getDataName($data)
    {
        $return = "";
        if($this->isAnInstance($data,self::TYPE_LIST))
        {
            $row = $data->getContent()->count()>0?$data->getContent()->get(0):null;

            if($this->isAnInstance($row,self::TYPE_ARTICLE)) $return = $this->getName(self::TYPE_LIST_ARTICLE);
            else if($this->isAnInstance($row,self::TYPE_STRING)) $return = $this->getName(self::TYPE_LIST_STRING);
            else $return = $this->getName(self::TYPE_LIST);
        }
        else
        {
            if($this->isAnInstance($data,self::TYPE_ARTICLE)) $return = $this->getName(self::TYPE_ARTICLE);
            else if($this->isAnInstance($data,self::TYPE_STRING)) $return = $this->getName(self::TYPE_STRING);
            else if($this->isAnInstance($data,self::TYPE_DATE)) $return = $this->getName(self::TYPE_DATE);
            else if($this->isAnInstance($data,self::TYPE_INTEGER)) $return = $this->getName(self::TYPE_INTEGER);
        }
        return $return;
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
            $instance = new DataList();
            $r = new DataArticle();
            $array = new ArrayCollection();
            $array->add($r);
            $instance->setContent($array);
        }

        return $instance;
    }

    /**
     * Check if the given Data object match with the given type
     * @param $data Data
     * @param $type Integer
     */
    public function isAnInstance($data,$type)
    {
        $result= false;
        if($type == self::TYPE_ARTICLE && $data instanceof DataArticle) $result = true;
        else if($type == self::TYPE_STRING && $data instanceof DataString) $result = true;
        else if($type == self::TYPE_DATE && $data instanceof DataDate) $result = true;
        else if($type == self::TYPE_INTEGER && $data instanceof DataInteger) $result = true;
        else if($type == self::TYPE_LIST && $data instanceof DataList) $result = true;
        else if($type == self::TYPE_LIST_ARTICLE && $data instanceof DataList) $result = true;
        else if($type == self::TYPE_LIST_STRING && $data instanceof DataList) $result = true;

        return $result;
    }


}