<?php

namespace Kraken\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class User
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kraken\Repositories\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser{

    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * List of linked scenario
     * @ORM\OneToMany(targetEntity="Scenario", mappedBy="user",cascade={"persist","remove"},orphanRemoval=true)
     * @ORM\OrderBy({"dateCreation" = "DESC"})
     */
    protected $scenarios;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setScenarios($scenarios)
    {
        $this->scenarios = $scenarios;
    }

    public function getScenarios()
    {
        return $this->scenarios;
    }



    /**
     * PostLoad Function
     *
     * @ORM\PostLoad
     */
    private function postLoad(){

        if($this->scenarios==null)$this->scenarios = new ArrayCollection();


    }

}