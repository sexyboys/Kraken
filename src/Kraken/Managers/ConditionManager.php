<?php

namespace Kraken\Managers;

use Kraken\Entities\Condition;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class ConditionManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class ConditionManager extends BaseManager {


    public function __construct($em,Logger $logger)
    {
        $this->em = $em;
        $this->logger=$logger;
    }

    public function getRepository()
    {
        return $this->em->getRepository('Kraken\Entities\Condition');
    }

    /**
     * Create an entity
     * @return the new entity
     */
    public function create(){
        $this->logger->info('[ConditionManager]Create new Condition');
        return new Condition();
    }

}