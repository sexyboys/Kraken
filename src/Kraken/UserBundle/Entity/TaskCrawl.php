<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class describe a TaskCrawl
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskCrawl extends Task{

    /**
     * Data resulting of the crawl
     * @ORM\ManyToOne(targetEntity="Data")
     * @ORM\JoinColumn(name="data_id", referencedColumnName="id")
     */
    protected $data;

    /**
     * Link of the crawl
     * @ORM\Column(name="crawl_link", type="string",nullable=true)
     */
    protected $link;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function getLink()
    {
        return $this->link;
    }




}
