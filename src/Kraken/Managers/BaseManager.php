<?php
/**
 * User: erik
 * @version 1.0
 */

namespace Kraken\Managers;


/**
 * Class BaseManager
 * @package Kraken\Managers
 * @author Eric Pidoux
 * @version 1.0
 */
abstract class BaseManager {

    /**
     * Entity manager
     * @var entity manager
     */
    protected $em;

    /**
     * Logger
     * @var Logger
     */
    protected $logger;

    /**
     * Load an entity with its id
     * @param $id
     * @return an entity
     */
    public function find($id) {
        $this->logger->debug('[EntityManager]Loading Entity with id '.$id);
        return $this->getRepository()
            ->find($id);
    }

    /**
     * Return the repository of the entity
     * @return object the repository
     */
    public abstract function getRepository();

    /**
     * Delete entity
     * @param $entity
     */
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * Update
     * @param $entity
     */
    public function update($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

}