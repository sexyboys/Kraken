<?php
namespace Kraken\Entities\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a Task
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="Kraken\Repositories\TaskRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *                        "task" = "Task",
 *                        "crawl_web" = "TaskCrawlWeb",
 *                        "action_translate" = "TaskActionTranslate",
 *                        "sender_email" = "TaskSenderEmail",
 *                        "sender_blog" = "TaskSenderBlog",
 *                        "sender_social" = "TaskSenderSocial"})
 */
class Task {
    /**
     * @var integer the id
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Name of the task
     * @ORM\Column(name="name", type="string",nullable=true)
     */
    protected $name;

    /**
     * Description of the task
     * @ORM\Column(name="description", type="text",nullable=true)
     */
    protected $description;

    /**
     * Position of the task in the scenario order
     * @ORM\Column(name="position",type="integer",nullable=true)
     */
    protected $position;

    /**
     * Conditions to not execute the task
     * @ORM\OneToMany(targetEntity="Kraken\Entities\Condition", mappedBy="task", cascade={"persist", "remove"})
     */
    protected $conditions;

    /**
     * Input Data
     * @ORM\ManyToOne(targetEntity="Kraken\Entities\Data\Data")
     * @ORM\JoinColumn(name="input_data_id", referencedColumnName="id")
     *
     */
    protected $input_data;

    /**
     * Output Data
     * @ORM\ManyToOne(targetEntity="Kraken\Entities\Data\Data")
     * @ORM\JoinColumn(name="output_data_id", referencedColumnName="id")
     *
     */
    protected $output_data;

    /**
     * Scenario linked
     * @ORM\ManyToOne(targetEntity="Kraken\Entities\Scenario", inversedBy="tasks")
     * @ORM\JoinColumn(name="scenario_id", referencedColumnName="id")
     */
    protected $scenario;



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

    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    public function getConditions()
    {
        return $this->conditions;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setInputData($input_data)
    {
        $this->input_data = $input_data;
    }

    public function getInputData()
    {
        return $this->input_data;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setOutputData($output_data)
    {
        $this->output_data = $output_data;
    }

    public function getOutputData()
    {
        return $this->output_data;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setScenario($scenario)
    {
        $this->scenario = $scenario;
    }

    public function getScenario()
    {
        return $this->scenario;
    }


}
