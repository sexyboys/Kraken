<?php
namespace Kraken\Entities\Data;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a data article
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class DataArticle extends Data {
    /**
     * @var string the title
     * @ORM\Column(type="string",nullable=true)
     */
    protected $title;

    /**
     * @var \DateTime the date
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $date;

    /**
     * @var string the content
     * @ORM\Column(type="string",nullable=true)
     */
    protected $content;

    /**
     * @var string the source
     * @ORM\Column(type="string",nullable=true)
     */
    protected $source;

    /**
     * @return the string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) {
        $this->title = $title;
    }

    /**
     * @return the DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date) {
        $this->date = $date;
    }

    /**
     * @return the string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content) {
        $this->content = $content;
    }

    /**
     * @return the string
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source) {
        $this->source = $source;
    }

}