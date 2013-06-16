<?php

namespace Kraken\Managers;

use Kraken\Entities\Task\Task;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * Class TaskManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class TaskManager extends BaseManager {

    protected $translator;

    public function __construct($em,Logger $logger, Translator $trans)
    {
        $this->em = $em;
        $this->logger=$logger;
        $this->translator = $trans;
    }


    public function getRepository()
    {
        return $this->em->getRepository('Kraken\Entities\Task\Task');
    }

    /**
     * Load types of tasks
     * @return array of types tasks
     */
    public function loadTypes()
    {
        $array = array();
        $array[]=$this->translator->trans('TaskCrawlWeb');
        $array[]=$this->translator->trans('TaskActionTranslate');
        $array[]=$this->translator->trans('TaskSenderEmail');
        $array[]=$this->translator->trans('TaskSenderBlog');
        $array[]=$this->translator->trans('TaskSenderSocial');
        return $array;
    }

    /**
     * Create an entity
     * @return the new entity
     */
    public function create(){
        $this->logger->info('[TaskManager]Create new Task');
        return new Task();
    }

}