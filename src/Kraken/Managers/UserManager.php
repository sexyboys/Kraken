<?php
namespace Kraken\Managers;

use Kraken\UserBundle\Entity\User;
use Symfony\Bridge\Monolog\Logger;
/**
 * Class UserManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
class UserManager extends BaseManager {

    public function __construct($em,Logger $logger)
    {
        $this->em = $em;
        $this->logger=$logger;
    }


    public function getRepository()
    {
        return $this->em->getRepository('Kraken\UserBundle\Entity\User');
    }

    /**
     * Create an entity
     * @return the new entity
     */
    public function create(){
        $this->logger->info('[UserManager]Create new user');
        return new User();
    }

    /**
     * Find all active User
     * @return the collection
     */
    public function findAllActive()
    {
        return $this->getRepository()->findAllActive();
    }

    /**
     * count all active User
     * @return the collection
     */
    public function countAllActive()
    {
        return $this->getRepository()->countAllActive();
    }
}