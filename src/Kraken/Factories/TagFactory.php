<?php

namespace Kraken\Factories;

use Doctrine\Common\Collections\ArrayCollection;
use Inflexible\Inflexible;

/**
 * Class TagFactory
 * @package Kraken\Factories
 * @author Eric Pidoux
 * @version 1.0
 */
class TagFactory {

    const TYPE_MOTHER="tagMother";

    const TYPE_TITLE="tagTitle";

    const TYPE_CONTENT="tagContent";

    const TYPE_DATE="tagDate";

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
            self::$_instance = new TagFactory();
        }
        return self::$_instance;
    }

    /**
     * Retrieve tag by its type
     * @param tags ArrayCollection list of tags
     * @param type String the type needed
     * @return Tag
     */
    public function getTag($tags,$type)
    {

        $result = null;
        foreach($tags as $tag)
        {
            if($tag->getType() == $type)
            {
                $result=$tag;
                break;
            }
        }
        return $result;
    }

}