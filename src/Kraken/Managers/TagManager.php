<?php

namespace Kraken\Managers;

use Kraken\UserBundle\Entity\Tag;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class TagManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class TagManager extends BaseManager {


    public function __construct($em,Logger $logger)
    {
        $this->em = $em;
        $this->logger=$logger;
    }


    public function getRepository()
    {
        return $this->em->getRepository('Kraken\UserBundle\Entity\Tag');
    }

    /**
     * Create an entity
     * @return the new entity
     */
    public function create(){
        $this->logger->info('[TagManager]Create new tag');
        return new Tag();
    }
}