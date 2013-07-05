<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class describe a TaskActionTranslate
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskActionTranslate extends TaskAction {


    /**
     * Original language
     * @ORM\Column(name="action_translate_lang_original",type="string",nullable=true)
     */
    protected $languageOriginal;

    /**
     * Needed language
     * @ORM\Column(name="action_translate_lang_needed",type="string",nullable=true)
     */
    protected $languageNeeded;

    public function setLanguageNeeded($languageNeeded)
    {
        $this->languageNeeded = $languageNeeded;
    }

    public function getLanguageNeeded()
    {
        return $this->languageNeeded;
    }

    public function setLanguageOriginal($languageOriginal)
    {
        $this->languageOriginal = $languageOriginal;
    }

    public function getLanguageOriginal()
    {
        return $this->languageOriginal;
    }



}
