<?php
namespace Kraken\Entities\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class describe a TaskAction
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskAction extends Task{

    /**
     * Keep original content
     * @ORM\Column(name="action_keep_original_content",type="string",nullable=true)
     */
    protected $keepOriginalContent;

    public function setKeepOriginalContent($keepOriginalContent)
    {
        $this->keepOriginalContent = $keepOriginalContent;
    }

    public function getKeepOriginalContent()
    {
        return $this->keepOriginalContent;
    }






}
