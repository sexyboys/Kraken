<?php

namespace Kraken\Factories;

use Doctrine\Common\Collections\ArrayCollection;
use Inflexible\Inflexible;
use Kraken\AdminBundle\Form\Type\TaskActionTranslateType;
use Kraken\AdminBundle\Form\Type\TaskCrawlWebType;
use Kraken\AdminBundle\Form\Type\TaskSenderBlogType;
use Kraken\AdminBundle\Form\Type\TaskSenderEmailType;
use Kraken\AdminBundle\Form\Type\TaskSenderSocialType;
use Kraken\Entities\Task\TaskActionTranslate;
use Kraken\Entities\Task\TaskCrawlWeb;
use Kraken\Entities\Task\TaskSenderBlog;
use Kraken\Entities\Task\TaskSenderEmail;
use Kraken\Entities\Task\TaskSenderSocial;

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