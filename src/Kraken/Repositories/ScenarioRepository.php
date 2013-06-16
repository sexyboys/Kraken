<?php
namespace Kraken\Repositories;

use Doctrine\ORM\EntityRepository;

class ScenarioRepository extends EntityRepository{

    /**
     * Find all active Scenario (not models)
     * @return the collection
     */
    public function findAllActiveScenario()
    {
        $result = $this->_em->createQuery('
		        SELECT
		            u
		        FROM
		            Kraken\Entities\Scenario u
		        WHERE
		            u.active = 1
				AND
					u.user IS NULL
		    ')
        ->getResult();
        return $result;
    }

    /**
     * Count all active Scenario (not models)
     * @return the collection
     */
    public function countAllActiveScenario()
    {
        $result = $this->_em->createQuery('
		        SELECT
		            COUNT(u)
		        FROM
		            Kraken\Entities\Scenario u
		        WHERE
		            u.active = 1
				AND
					u.user IS NULL
		    ')
            ->getResult();
        return $result[0][1];
    }


    /**
     * Find all active Scenario (models)
     * @return the collection
     */
    public function findAllActiveModel()
    {
        $result = $this->_em->createQuery('
		        SELECT
		            u
		        FROM
		            Kraken\Entities\Scenario u
		        WHERE
		            u.active = 1
				AND
					u.user IS NULL
		    ')
            ->getResult();
        return $result;
    }

    /**
     * Count all active Scenario (models)
     * @return the collection
     */
    public function countAllActiveModel()
    {
        $result = $this->_em->createQuery('
		        SELECT
		            COUNT(u)
		        FROM
		            Kraken\Entities\Scenario u
		        WHERE
		            u.active = 1
				AND
					u.user IS NULL
		    ')
            ->getResult();
        return $result[0][1];
    }


    /**
     * Find all scenario
     */
    public function findAll()
    {
        $result = $this->_em->createQuery('
		        SELECT
		            u
		        FROM
		            Kraken\Entities\Scenario u
		    ')
            ->getResult();
        return $result;
    }


    /**
     * Find all active
     * @return the collection
     */
    public function findAllModels()
    {
        $result = $this->_em->createQuery('
		        SELECT
		            u
		        FROM
		            Kraken\Entities\Scenario u
		        WHERE
					u.user IS NULL
		    ')
            ->getResult();
        return $result;
    }

}