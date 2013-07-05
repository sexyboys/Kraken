<?php
namespace Kraken\Repositories;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository{

    /**
     * Count all active
     * @return the collection
     */
    public function countAllActive()
    {
        $result = $this->_em->createQuery('
		        SELECT
		            COUNT(u)
		        FROM
		            Kraken\UserBundle\Entity\User u
		        WHERE
		            u.locked = 0
                AND
                    u.enabled = 1
		    ')
            ->getResult();
        return $result[0][1];
    }


    /**
     * Find all active
     * @return the collection
     */
    public function findAllActive()
    {
        $result = $this->_em->createQuery('
		        SELECT
		            u
		        FROM
		            Kraken\UserBundle\Entity\User u
		        WHERE
		            u.locked = 0
                AND
                    u.enabled = 1
		    ')
            ->getResult();
        return $result;
    }

}