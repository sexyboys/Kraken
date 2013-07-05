<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a tag
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity(repositoryClass="Kraken\Repositories\TagRepository")
 * @ORM\Table()
 */
class Tag{


    /**
     * @var integer the id
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string the name
     * @ORM\Column(name="name",type="string",nullable=true)
     */
    protected $name;

    /**
     * Attribut identifiant (partial if needed or regex)
     * @ORM\Column(name="regex",type="string",nullable=true)
     */
    protected $regex;

    /**
     * Task linked to the task
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="tags")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    protected $task;

    /**
     * Type of tag
     * @ORM\Column(name="type_tag",type="string",nullable=false)
     */
    protected $type;

    /**
     * @return the integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param  $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    public function getRegex()
    {
        return $this->regex;
    }



    /**
     * @param $tag
     */
    public function setName($tag)
    {
        $this->name = $tag;
    }

    /**
     * @return $tag
     */
    public function getName()
    {
        return $this->name;
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }



}
