<?php

namespace Kraken\Managers;

use Kraken\UserBundle\Entity\Task;
use Kraken\UserBundle\Entity\TaskActionTranslate;
use Kraken\UserBundle\Entity\TaskCrawlWeb;
use Kraken\UserBundle\Entity\TaskSenderBlog;
use Kraken\UserBundle\Entity\TaskSenderEmail;
use Kraken\UserBundle\Entity\TaskSenderSocial;
use Kraken\UserBundle\Entity\Condition;
use Kraken\Factories\DataFactory;
use Kraken\Factories\TaskFactory;
use Kraken\UserBundle\Entity\DataString;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Inflexible\Inflexible;
use Kraken\Managers\Services\WebCrawlerService;
use Kraken\Managers\Services\BlogService;
use Kraken\Managers\Services\EmailService;
use Kraken\Managers\Services\SocialService;
use Kraken\Managers\Services\TranslateService;
use Kraken\Managers\Services\DataTransformerService;
use Kraken\Managers\Services\DisplayLogService;
use Kraken\Managers\Services\XmlService;

/**
 * Class TaskManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class TaskManager extends BaseManager {

    /**
     * Translator service
     * @var \Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    private $translator;

    private $webcrawlerService;

    private $translateService;

    private $blogService;

    private $emailService;

    private $socialService;

    private $dataTransformerService;

    protected $displayLog;

    protected $xmlService;

    public function __construct($em,Logger $logger, Translator $trans,
                                WebCrawlerService $webcrawlerService,
                                BlogService $blogService,
                                EmailService $emailService,
                                SocialService $socialService,
                                TranslateService $translateService,
                                DataTransformerService $dataTransformerService,
                                DisplayLogService $displayLog,
                                XmlService $xmlService
    )
    {
        $this->em = $em;
        $this->logger=$logger;
        $this->translator = $trans;
        $this->webcrawlerService = $webcrawlerService;
        $this->blogService = $blogService;
        $this->emailService = $emailService;
        $this->socialService = $socialService;
        $this->translateService = $translateService;
        $this->dataTransformerService = $dataTransformerService;
        $this->displayLog = $displayLog;
        $this->xmlService = $xmlService;
    }


    public function getRepository()
    {
        return $this->em->getRepository('Kraken\UserBundle\Entity\Task');
    }


    /**
     * Reload a Task content datas types
     * @param $task Task to reload
     */
    public function reloadTask($task)
    {
        //define the type of Task
        $index = TaskFactory::getInstance()->getTypeIndex(Inflexible::denamespace(get_class($task)));
        $array_datas_in = TaskFactory::getInstance()->defineDataByTask($index,true);
        $array_datas_out = TaskFactory::getInstance()->defineDataByTask($index,false);

        //push data in
        if($array_datas_in->get(0) == DataFactory::NONE ){
            //delete data
            $task->setChosenInputData("DataNone");
        }
        else if($array_datas_in->get(0) == DataFactory::SAME)
        {
            $task->setChosenInputData("DataSame");

        }

        //push data out
        if($array_datas_out->get(0) == DataFactory::NONE)
        {
            //delete data
            $task->setChosenOutputData("DataNone");
        }
        else if($array_datas_out->get(0) == DataFactory::SAME)
        {
            //same data that input
            $task->setChosenOutputData("DataSame");
        }
        return true;
    }

    /**
     * Reload Task content such as input/output datas default types
     * @param tasks list of task
     */
    public function reloadTasks($tasks)
    {
        $last_task = null;
        foreach($tasks as $task)
        {
          $this->reloadTask($task);
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

    /**
     * Execute Task
     * @param $task Task which is executed
     * @param $content the content out from the previous task
     * @return $data Data returned
     */
    public function execute($task,$content=null)
    {
        try
        {
            $classname = Inflexible::denamespace(get_class($task));
            if($classname == TaskFactory::TASK_CRAWL_WEB_CLASSNAME)
            {
                //TaskCrawlWeb
                $out = $this->webcrawlerService->extractData(
                    $task->getLink(),
                    $task->getMultipageRegex(),
                    $task->getMultipageLimit(),
                    $task->getLinkMoreRegex(),
                    $task->getTags(),
                    $task->getChosenOutputData()
                );
<<<<<<< HEAD

=======
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883
                //Check if exists double contents if true, remove doubles
                $out_purged = $this->dataTransformerService->purgeDoubles($task,$out);

                //add Data to task
                $task->setData($out_purged);
                $out = $out_purged;
            }
            else if($classname == TaskFactory::TASK_ACTION_TRANSLATE_CLASSNAME)
            {
                //TaskActionTranslate
                $out = $this->translateService->translate($content,$task->getLanguageOriginal(),$task->getLanguageNeeded(),$task->getKeepOriginalContent());

            }
            else if($classname == TaskFactory::TASK_ACTION_ARRANGER_TEXT_CLASSNAME)
            {
                //TaskActionArrangerText
                $data_xml = $this->xmlService->transformDataToXml($task,$content);
                $out = $this->xmlService->transformXmlWithXslt($task->getXslt(),$data_xml);


            }
            else if($classname == TaskFactory::TASK_SENDER_EMAIL_CLASSNAME)
            {
                //TaskSenderEmail
                $this->emailService->prepare($task,$content);
                $out = $content;
            }
            else if($classname == TaskFactory::TASK_SENDER_BLOG_CLASSNAME)
            {
                //TaskSenderBlog
                $out = $this->blogService->prepare($task,$content,null);
            }
            else if($classname == TaskFactory::TASK_SENDER_SOCIAL_CLASSNAME)
            {
                //TaskSenderSocial
<<<<<<< HEAD
                $out = $this->socialService->prepare($task,$content,null);
=======
                $out = $this->socialService->sendSocial($content,null);
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883

            }

            //MERGE RETURNED CONTENT INTO WHAT EXPECTED
            $out = $this->dataTransformerService->transform($out,DataFactory::getInstance()->getIndex($task->getChosenOutputData()));

            //TODO UPDATE TASK

        }
        catch(\Exception $e)
        {
            $this->logger->err("[TaskManager] Error while executing Task ".$task->getId()." to display : ".$e->getCode());
           // $this->displayLog->display('execute.display.task.error',array("%msg%"=>$e->getCode()),DisplayLogService::TYPE_ERROR, $e);

            throw $e;
        }
        return $out;
    }

    /**
     * Execute task and return result to display
     * @param $task Task
     * @param $content the last content to send
     * @return Data result of task
     */
    public function executeToDisplay($task,$content=null)
    {

        try
        {

            $log = $this->translator->trans('execute.display.task.start',
                array(
                    "%name%" => $task->getName(),
                    "%type%" => Inflexible::denamespace(get_class($task))
                )
            );
            $this->displayLog->display('execute.display.task.start',array( "%name%" => $task->getName(),"%type%" => Inflexible::denamespace(get_class($task))),DisplayLogService::TYPE_INFO);


            $this->logger->info("[TaskManager] ".$log);


            $output = $this->execute($task,$content);


            if(
                DataFactory::getInstance()->isAnInstance($output,DataFactory::TYPE_LIST_ARTICLE)
               || DataFactory::getInstance()->isAnInstance($output,DataFactory::TYPE_LIST_STRING)
               || DataFactory::getInstance()->isAnInstance($output,DataFactory::TYPE_LIST)
            )
            {
                $list = $output->getContent();
                //TODO check the translation display

                if(DataFactory::getInstance()->isAnInstance($output,DataFactory::TYPE_LIST_ARTICLE))
                {
                    /*if($task->getId()==9){
                        print_r($list->get(0));exit;
                    }*/
                    $content = Inflexible::shortenString($list->get(0)->getContent());

                    $firstContent = $list->get(0)->getTitle()." // ".$content;
                }
                else{
                    $firstContent = $list->get(0)->getContent();

                }
                $excerpt = $this->translator->trans('execute.display.output.list',
                        array(
                            "%count%"=> count($list),
                            "%first_content%" => $firstContent
                        )
                );
            }
            else
            {
                $content = Inflexible::shortenString($output->getContent());
                $excerpt = $this->translator->trans('execute.display.output.single',
                    array(
                        "%content%" => $content
                    )
                );
            }

            $log = $this->translator->trans('execute.display.task.end',
                array(
                    "%name%" => $task->getName(),
                    "%excerpt%" => $excerpt
                ));

            $this->displayLog->display('execute.display.task.end',array( "%name%" => $task->getName(),"%excerpt%" => $excerpt),DisplayLogService::TYPE_SUCCESS);

            $this->logger->info("[TaskManager] ".$log);
        }
        catch(\Exception $e)
        {
            $this->logger->err("[TaskManager] Error while executing Task ".$task->getId()." to display : ".$e->getMessage());
           // $this->displayLog->display('execute.display.task.error',array("%msg%"=>$e->getCode()),DisplayLogService::TYPE_ERROR, $e);

            throw $e;
        }
        return $output;
    }


}