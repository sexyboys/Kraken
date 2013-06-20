<?php
namespace Kraken\Entities\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a TaskSenderEmail
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskSenderEmail extends TaskSender{


    /**
     * List of emails who are recievers
     * @ORM\Column(name="emails",nullable=true)
     */
    private $stringEmails;

    /**
     * @var ArrayCollection of String
     */
    protected $emails;

    /**
     * Object of the email
     * @ORM\Column(name="object",type="string",nullable=true)
     */
    protected $object;

    /**
     * Content of the email
     * @ORM\Column(name="content",type="text",nullable=true)
     */
    protected $content;


    public function getStringEmails()
    {
        return $this->stringEmails;
    }

    public function setStringEmails($emails)
    {
        $this->stringEmails=$emails;
    }

    private function refreshStringEmails()
    {
        $this->stringEmails = "";
        foreach($this->emails as $email)
        {
            $this->stringEmails.=$email.";";
        }
    }

    public function getEmails()
    {
        if($this->emails == null)
        {
            $this->emails = new ArrayCollection();
            $tab = explode(";",$this->getStringEmails());
            foreach($tab as $element)
            {
                if($element!="")$this->emails->add($element);
            }

        }
        return $this->emails;
    }

    public function setEmails($emails)
    {
        $this->emails = $emails;
        $str = "";
        foreach($this->emails as $email)
        {
            $str.=$email.";";
        }
        $this->setStringEmails($str);
    }

    public function addEmail($email)
    {
        $this->getEmails()->add($email);
        $this->setStringEmails($this->getStringEmails().$email.";");
    }

    public function removeEmail($email)
    {
        $this->getEmails()->removeElement($email);
        $this->refreshStringEmails();
    }

    public function addEmails($emails)
    {
        foreach($emails as $email)
        {
            $this->addEmail($email);
        }
    }

    public function removeEmails($emails)
    {
        foreach($emails as $email)
        {
            $this->getEmails()->removeElement($email);
        }
        $this->refreshStringEmails();
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }



}
