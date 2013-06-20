<?php

namespace Kraken\Managers;

use Doctrine\Common\Collections\ArrayCollection;
use Kraken\AdminBundle\Form\Type\TaskActionTranslateType;
use Kraken\AdminBundle\Form\Type\TaskCrawlWebType;
use Kraken\AdminBundle\Form\Type\TaskSenderBlogType;
use Kraken\AdminBundle\Form\Type\TaskSenderEmailType;
use Kraken\AdminBundle\Form\Type\TaskSenderSocialType;
use Kraken\Entities\Task\Task;
use Kraken\Entities\Task\TaskActionTranslate;
use Kraken\Entities\Task\TaskCrawlWeb;
use Kraken\Entities\Task\TaskSenderBlog;
use Kraken\Entities\Task\TaskSenderEmail;
use Kraken\Entities\Task\TaskSenderSocial;
use Kraken\Entities\Condition;
use Kraken\Factories\DataFactory;
use Kraken\Factories\TaskFactory;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Inflexible\Inflexible;

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
     * Reload Task content such as input/output datas
     * @param tasks list of task
     */
    public function reloadTasks($tasks)
    {
        $last_task = null;
        foreach($tasks as $task)
        {
            //define the type of Task
            $index = TaskFactory::getInstance()->getTypeIndex(Inflexible::denamespace(get_class($task)));
            $array_datas_in = TaskFactory::getInstance()->defineDataByTask($index,true);
            $array_datas_out = TaskFactory::getInstance()->defineDataByTask($index,false);

            //push data in
            if($array_datas_in->get(0) == DataFactory::NONE ){
                //delete data
                $task->setInputData(null);
            }
            else if($array_datas_in->get(0) == DataFactory::SAME)
            {
                //same data that last output
                if($last_task!=null){
                    //previous
                    $task->setInputData($last_task->getOutputData());
                }
            }

            //push data out
            if($array_datas_out->get(0) == DataFactory::NONE)
            {
                //delete data
                $task->setOutputData(null);
            }
            else if($array_datas_out->get(0) == DataFactory::SAME)
            {
                //same data that input
                $task->setOutputData($task->getInputData());
            }
            //save last data
            $last_task=$task;
        }
    }

    /**
     * Reload Datas of a Task
     * @param $scenario the scenario instance
     * @param $task the task instance
     */
    public function reloadDatasByTask($scenario,$task)
    {

        //define the type of Task
        $index = TaskFactory::getInstance()->getTypeIndex(Inflexible::denamespace(get_class($task)));
        $array_datas_in = TaskFactory::getInstance()->defineDataByTask($index,true);
        $array_datas_out = TaskFactory::getInstance()->defineDataByTask($index,false);

        //push data in
        if($array_datas_in->get(0) == DataFactory::NONE ){
            //delete data
            $task->setInputData(null);
        }
        else if($array_datas_in->get(0) == DataFactory::SAME)
        {
            //same data that last output

            // get previous
            $ind = $scenario->getTasks()->indexOf($task);
            $last_task = $scenario->getTasks()->get($ind-1);
            $task->setInputData($last_task->getOutputData());

        }

        //push data out
        if($array_datas_out->get(0) == DataFactory::NONE)
        {
            //delete data
            $task->setOutputData(null);
        }
        else if($array_datas_out->get(0) == DataFactory::SAME)
        {
            //same data that input
            $task->setOutputData($task->getInputData());
        }

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