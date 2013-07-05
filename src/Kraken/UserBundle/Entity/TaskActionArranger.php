<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class describe a TaskActionArranger
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskActionArranger extends TaskAction {

    public function __construct(){
        $this->separateList = false;
    }

    /**
     * Separate List element
     * That option able to send one email by element if there is a TaskSenderEmail after.
     * @ORM\Column(name="separate_ist", type="boolean", nullable=true)
     */
    protected $separateList;

    public function setSeparateList($separateList)
    {
        $this->separateList = $separateList;
    }

    public function getSeparateList()
    {
        return $this->separateList;
    }

}
