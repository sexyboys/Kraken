<?php

namespace Kraken\Managers;

use Kraken\Entities\Scenario;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class ScenarioManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class ScenarioManager extends BaseManager {


    public function __construct($em,Logger $logger)
    {
        $this->em = $em;
        $this->logger=$logger;
    }


    public function getRepository()
    {
        return $this->em->getRepository('Kraken\Entities\Scenario');
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



}