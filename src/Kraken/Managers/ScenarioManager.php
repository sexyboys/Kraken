<?php

namespace Kraken\Managers;

use Kraken\UserBundle\Entity\Scenario;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Kraken\Managers\Services\DisplayLogService;

/**
 * Class ScenarioManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class ScenarioManager extends BaseManager {

    protected $translator;

    protected $taskManager;

    protected $displayLog;

    public function __construct($em,Logger $logger,Translator $trans,TaskManager $taskManager, DisplayLogService $displayLog)
    {
        $this->em = $em;
        $this->logger=$logger;
        $this->translator = $trans;
        $this->taskManager = $taskManager;
        $this->displayLog = $displayLog;
    }


    public function getRepository()
    {
        return $this->em->getRepository('Kraken\UserBundle\Entity\Scenario');
    }

    /**
     * Create an entity
     * @return the new entity
     */
    public function create(){
        $this->logger->info('[ScenarioManager]Create new scenario');
        return new Scenario();
    }

    /**
     * Find all active Scenario (not models)
     * @return the collection
     */
    public function findAllActiveScenario()
    {
        return $this->getRepository()->findAllActiveScenario();
    }

    /**
     * count all active Scenario (not models)
     * @return the collection
     */
    public function countAllActiveScenario()
    {
        return $this->getRepository()->countAllActiveScenario();
    }

    /**
     * Find all active Scenario (models)
     * @return the collection
     */
    public function findAllActiveModel()
    {
        return $this->getRepository()->findAllActiveModel();
    }

    /**
     * count all active Scenario (models)
     * @return the collection
     */
    public function countAllActiveModel()
    {
        return $this->getRepository()->countAllActiveModel();
    }

    /**
     * Find all scenario
     */
    public function findAllModels()
    {
        return $this->getRepository()->findAllModels();
    }

    /**
     * Execute scenario (to the given task) and return result to display
     * @param $scenario Scenario to execute
     * @param $given_task Task to end execution
     * @return array of log execution display to the user
     * @throws \Exception if operation fail
     */
    public function executeToDisplay($scenario,$given_task=null)
    {

        try{
            $log = $this->translator->trans('execute.display.scenario.start',
                array(
                    "%name%" => $scenario->getName(),
                    "%count%" => $scenario->getTasks()->count()
                ));
            $this->displayLog->display('execute.display.scenario.start',array( "%name%" => $scenario->getName(),"%count%" => $scenario->getTasks()->count()),DisplayLogService::TYPE_INFO);

            $this->logger->info("[ScenarioManager] ".$log);
            $result = null;

            foreach($scenario->getTasks() as $task)
            {
                if($task!=null)
                {
                   $result = $this->taskManager->executeToDisplay($task,$result);

                }
                //stop if it's the given task
                if($task->getId() == $given_task->getId())break;
            }
            $log = $this->translator->trans('execute.display.scenario.end',
                array(
                    "%name%" => $scenario->getName()
                ));

            $this->displayLog->display('execute.display.scenario.end',array( "%name%" => $scenario->getName()),DisplayLogService::TYPE_INFO);
            $this->logger->info("[ScenarioManager] ".$log);
        }
        catch(\Exception $e)
        {
            $this->logger->err("[ScenarioManager] Error while executing ".$scenario->getId()." to display : ".$e->getMessage());
            //$this->displayLog->display('execute.display.scenario.error',array("%msg%"=>$e->getCode()),DisplayLogService::TYPE_ERROR, $e);

            throw $e;
        }

        return $result;
    }


}