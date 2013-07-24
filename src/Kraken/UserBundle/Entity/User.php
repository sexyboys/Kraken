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
<<<<<<< HEAD
     * @var string
     * @ORM\Column(name="twitterId", type="string",nullable=true)
     */
    protected $twitterID;

    /**
     * @var string
     * @ORM\Column(name="twitterUsername", type="string",nullable=true)
     */
    protected $twitter_username;

    /**
=======
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883
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


<<<<<<< HEAD
    /**
     * Set twitterID
     *
     * @param string $twitterID
     */
    public function setTwitterID($twitterID)
    {
        $this->twitterID = $twitterID;
        $this->setUsername($twitterID);
        $this->salt = '';
    }

    /**
     * Get twitterID
     *
     * @return string
     */
    public function getTwitterID()
    {
        return $this->twitterID;
    }

    /**
     * Set twitter_username
     *
     * @param string $twitterUsername
     */
    public function setTwitterUsername($twitterUsername)
    {
        $this->twitter_username = $twitterUsername;
    }

    /**
     * Get twitter_username
     *
     * @return string
     */
    public function getTwitterUsername()
    {
        return $this->twitter_username;
    }
=======
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883

    /**
     * PostLoad Function
     *
     * @ORM\PostLoad
     */
    private function postLoad(){

        if($this->scenarios==null)$this->scenarios = new ArrayCollection();


    }

}