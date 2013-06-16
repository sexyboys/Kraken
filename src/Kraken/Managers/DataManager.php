<?php

namespace Kraken\Managers;


use Kraken\Entities\Data\Data;
use Symfony\Bridge\Monolog\Logger;
/**
 * Class DataManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class DataManager extends BaseManager {


    public function __construct($em,Logger $logger)
    {
        $this->em = $em;
        $this->logger=$logger;
    }


    public function getRepository()
    {
        return $this->em->getRepository('Kraken\Entities\Data\Data');
    }

    /**
     * Create an entity
     * @return the new entity
     */
    public function create(){
        $this->logger->info('[DataManager]Create new data');
        return new Data();
    }

}