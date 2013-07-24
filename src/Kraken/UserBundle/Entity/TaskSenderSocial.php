<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describes a TaskSenderSocial
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskSenderSocial extends TaskSender{


<<<<<<< HEAD
    /**
     * login
     * @ORM\Column(name="login",type="string",nullable=false)
     */
    protected $login;

    /**
     * pass
     * @ORM\Column(name="pass",type="string",nullable=false)
     */
    protected $pass;

    /**
     * type name
     * @ORM\Column(name="social_type",type="string",nullable=false)
     */
    protected $socialType;

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setSocialType($type)
    {
        $this->socialType = $type;
    }

    public function getSocialType()
    {
        return $this->socialType;
    }



=======
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883
}
