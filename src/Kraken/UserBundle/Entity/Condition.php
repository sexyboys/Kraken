<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Conditions are pre-generated for all kind of Task
 * Class Condition
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Table(name="condition_task")
 * @ORM\Entity(repositoryClass="Kraken\Repositories\ConditionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Condition {

    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name of the Condition
     * @ORM\Column(name="name", type="string",nullable=true)
     */
    protected $name;

    /**
     * Description of the Condition
     * @ORM\Column(name="description", type="text",nullable=true)
     */
    protected $description;

    /**
     * @var Data $data
     * @ORM\ManyToOne(targetEntity="Data", cascade={"persist", "remove"})
     */
    protected $data;

    /**
     * Position type of the condition
     * @var string $positionType
     * @ORM\Column(name="position_type",type="string",nullable=true)
     */
    protected $positionType;

    /**
     * Position regex
     * @var String $positionRegex
     * @ORM\Column(name="position_regex",type="string",nullable=true)
     */
    protected $positionRegex;

    /**
     * Sign of the condition >,<,=,!=,<=,>=
     * @var String $sign
     * @ORM\Column(name="sign_condition",type="string",nullable=true)
     */
    protected $sign;

    /**
     * Task linked to this article
     * @var Task $task
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="conditions")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    protected $task;

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
     * @param Data $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param String $positionRegex
     */
    public function setPositionRegex($positionRegex)
    {
        $this->positionRegex = $positionRegex;
    }

    /**
     * @return String
     */
    public function getPositionRegex()
    {
        return $this->positionRegex;
    }

    /**
     * @param string $positionType
     */
    public function setPositionType($positionType)
    {
        $this->positionType = $positionType;
    }

    /**
     * @return string
     */
    public function getPositionType()
    {
        return $this->positionType;
    }

    /**
     * @param String $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return String
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param  $task
     */
    public function setTask($task)
    {
        $this->task = $task;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }


}