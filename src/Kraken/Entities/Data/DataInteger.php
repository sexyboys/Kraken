<?php

namespace Kraken\Entities\Data;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a data int
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class DataInteger extends Data {

    /**
     * @var integer the content
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $content;

    /**
     * @return the integer
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param integer $content
     */
    public function setContent(integer $content) {
        $this->content = $content;
    }



}