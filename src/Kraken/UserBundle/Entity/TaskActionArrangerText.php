<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a TaskActionArrangerText
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskActionArrangerText extends TaskActionArranger
{

    /**
     * Xslt provided by the user to transform data
     * @ORM\Column(name="xslt",type="text",nullable=true)
     */
     protected $xslt;

    public function setXslt($xslt)
    {
        $this->xslt = $xslt;
    }

    public function getXslt()
    {
        return $this->xslt;
    }

}
