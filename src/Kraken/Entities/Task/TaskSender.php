<?php
namespace Kraken\Entities\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class describe a TaskSender
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskSender extends Task{

    /**
     * Add source link to the content send (use regex if its email)
     * @ORM\Column(name="sender_add_source",type="boolean",nullable=true)
     */
    protected $addSource;

    public function setAddSource($addSource)
    {
        $this->addSource = $addSource;
    }

    public function getAddSource()
    {
        return $this->addSource;
    }




}
