<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class describe a TaskCrawlWeb (XML/HTML)
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskCrawlWeb extends TaskCrawl{

    public function __construct(){
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
<<<<<<< HEAD
        $this->multipageLimit=1;
=======
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883

    }

    /**
     * Tags linked
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="task", cascade={"persist", "remove"})
     */
    protected $tags;

    /**

    /**
     * Is there multi pages to crawl?if yes there is a regex as css (name .class #id)...
     * @ORM\Column(name="crawl_web_multipage_regex", type="string",nullable=true)
     */
    protected $multipageRegex;

    /**
     * Multipage pages number limit
     * @ORM\Column(name="crawl_web_multipage_limit", type="integer",nullable=true)
     */
    protected $multipageLimit;

    /**
     * Is there a link (suck as readmore) on each content? if yes there is a regex as css (name,class,id)...
     * @ORM\Column(name="crawl_web_link_more_regex",type="string",nullable=true)
     */
    protected $linkMoreRegex;

    public function setLinkMoreRegex($linkMoreRegex)
    {
        $this->linkMoreRegex = $linkMoreRegex;
    }

    public function getLinkMoreRegex()
    {
        return $this->linkMoreRegex;
    }

    public function setMultipageLimit($multipageLimit)
    {
        $this->multipageLimit = $multipageLimit;
    }

    public function getMultipageLimit()
    {
<<<<<<< HEAD
        if($this->multipageLimit==null) $this->multipageLimit=1;
=======
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883
        return $this->multipageLimit;
    }

    public function setMultipageRegex($multipageRegex)
    {
        $this->multipageRegex = $multipageRegex;
    }

    public function getMultipageRegex()
    {
        return $this->multipageRegex;
    }

    public function setTags($tags)
    {
        foreach($tags as $tag)
        {
            $tag->setTask($this);
        }
        $this->tags = $tags;
    }

    public function getTags()
    {
        return $this->tags;
    }

}
