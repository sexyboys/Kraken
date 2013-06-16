<?php
namespace Kraken\Entities\Tag;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class describe a tag
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity(repositoryClass="Kraken\Repositories\TagRepository")
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"mother" = "Tag", "title" = "TagTitle", "date" = "TagDate", "content" = "TagContent"})
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
     * @ORM\Column(name="identifiant",type="string",nullable=true)
     */
    protected $identifiant;

    /**
     * Attribut class
     * @ORM\Column(name="classe",type="string",nullable=true)
     */
    protected $classe;

    /**
     * Task linked to the task
     * @ORM\ManyToOne(targetEntity="Kraken\Entities\Task\Task", inversedBy="tags")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    protected $task;

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

    public function setClasse($classe)
    {
        $this->classe = $classe;
    }

    public function getClasse()
    {
        return $this->classe;
    }

    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;
    }

    public function getIdentifiant()
    {
        return $this->identifiant;
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



}
