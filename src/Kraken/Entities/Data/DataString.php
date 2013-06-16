<?php

namespace Kraken\Entities\Data;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a data string
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class DataString extends Data {

    /**
     * @var string the content
     * @ORM\Column(type="string",nullable=true)
     */
    protected $content;

    /**
     * @return the string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }



}