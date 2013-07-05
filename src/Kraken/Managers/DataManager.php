<?php

namespace Kraken\Managers;


use Doctrine\Common\Collections\ArrayCollection;
use Kraken\UserBundle\Entity\Data;
use Kraken\UserBundle\Entity\DataArticle;
use Kraken\UserBundle\Entity\DataDate;
use Kraken\UserBundle\Entity\DataInteger;
use Kraken\UserBundle\Entity\DataList;
use Kraken\UserBundle\Entity\DataString;
use Symfony\Bridge\Monolog\Logger;
/**
 * Class DataManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class DataManager extends BaseManager {



    protected $types;

    public function __construct($em,Logger $logger)
    {
        $this->em = $em;
        $this->logger=$logger;
    }



    public function getRepository()
    {
        return $this->em->getRepository('Kraken\UserBundle\Entity\Data');
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