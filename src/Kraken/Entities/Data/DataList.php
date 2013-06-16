<?php

namespace Kraken\Entities\Data;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a data list
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class DataList extends Data {

    /**
     * @var ArrayList(Data) the content
     * @ORM\ManyToMany(targetEntity="Data", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="datas_included",
     *      joinColumns={@ORM\JoinColumn(name="data_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="include_data_id", referencedColumnName="id")}
     *      )
     **/
    protected $content;

    /**
     * @return the ArrayList
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param ArrayList $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * PostLoad Function
     *
     * @ORM\PostLoad
     */
    private function postLoad(){

        if($this->content==null)$this->content = new ArrayCollection();


    }


}