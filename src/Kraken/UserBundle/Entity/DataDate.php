<?php
namespace Kraken\UserBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a data date
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class DataDate extends Data {

    /**
     * @var DateTime the content
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $content;

    /**
     * @return the DateTime
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param DateTime $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

}