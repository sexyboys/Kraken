<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Scenario
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kraken\Repositories\ScenarioRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Scenario {

    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     * @ORM\Column(name="name",type="string",nullable=true)
     */
    protected $name;

    /**
     * Description of the scenario
     * @var string description
     * @ORM\Column(name="description",type="text",nullable=true)
     */
    protected $description;

    /**
     * Date of creation
     * @var DateTime dateCreation
     * @ORM\Column(name="date_creation",type="datetime",nullable=true)
     */
    protected $dateCreation;

    /**
     * Date of the last execution
     * @var DateTime dateLastExecution
     * @ORM\Column(name="date_last_execution",type="datetime",nullable=true)
     */
    protected $dateLastExecution;

    /**
     * Number of minutes left between 2 executions
     * @var integer execMinutes
     * @ORM\Column(name="exec_minutes",type="integer",nullable=true)
     */
    protected $execMinutes;

    /**
     * List of linked tasks
     * @ORM\OneToMany(targetEntity="Task", mappedBy="scenario", cascade={"persist","remove"},orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $tasks;

    /**
     * User linked to this scenario
     * @var Scenario $scenario
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * Is scenario active
     * @var Boolean $active
     * @ORM\Column(name="active", type="boolean",nullable=true)
     */
    protected $active;

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

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param \DateTime $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param \DateTime $dateLastExecution
     */
    public function setDateLastExecution($dateLastExecution)
    {
        $this->dateLastExecution = $dateLastExecution;
    }

    /**
     * @return \DateTime
     */
    public function getDateLastExecution()
    {
        return $this->dateLastExecution;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $execMinutes
     */
    public function setExecMinutes($execMinutes)
    {
        $this->execMinutes = $execMinutes;
    }

    /**
     * @return int
     */
    public function getExecMinutes()
    {
        return $this->execMinutes;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param \Scenario $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Scenario
     */
    public function getUser()
    {
        return $this->user;
    }




    /**
     * PostLoad Function
     *
     * @ORM\PostLoad
     */
    private function postLoad(){

        if($this->tasks==null)$this->tasks = new ArrayCollection();


    }

}