<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a data
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Table(name="data")
 * @ORM\Entity(repositoryClass="Kraken\Repositories\DataRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
*                   "article" = "DataArticle",
 *                  "string" = "DataString",
 *                  "integer" = "DataInteger",
 *                  "date" = "DataDate",
 *                  "list" = "DataList"
 * })
 */
abstract class Data {
    /**
     * @var integer the id
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @return the integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param  $id
     */
    public function setId($id) {
        $this->id = $id;
    }


}
