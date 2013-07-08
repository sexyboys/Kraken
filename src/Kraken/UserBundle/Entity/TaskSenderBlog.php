<?php
namespace Kraken\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class describe a TaskSenderBlog
 * @author Eric Pidoux
 * @version 1.0
 * @ORM\Entity
 */
class TaskSenderBlog extends TaskSender{


    /**
     * Blog user login
     * @ORM\Column(name="blog_login",type="string",nullable=false)
     */
    protected $blogLogin;

    /**
     * Blog user pass
     * @ORM\Column(name="blog_pass",type="string",nullable=false)
     */
    protected $blogPass;

    /**
     * Blog email
     * @ORM\Column(name="blog_email",type="string",nullable=false)
     */
    protected $blogEmail;

    /**
     * Blog link example with wordpress(link)/xml-rpc.php
     * @ORM\Column(name="blog_link",type="string",nullable=false)
     */
    protected $blogLink;

    /**
     * Blog type name
     * @ORM\Column(name="blog_type",type="string",nullable=true)
     */
    protected $blogType;

    public function setBlogEmail($blogEmail)
    {
        $this->blogEmail = $blogEmail;
    }

    public function getBlogEmail()
    {
        return $this->blogEmail;
    }

    public function setBlogLink($blogLink)
    {
        $this->blogLink = $blogLink;
    }

    public function getBlogLink()
    {
        return $this->blogLink;
    }

    public function setBlogLogin($blogLogin)
    {
        $this->blogLogin = $blogLogin;
    }

    public function getBlogLogin()
    {
        return $this->blogLogin;
    }

    public function setBlogPass($blogPass)
    {
        $this->blogPass = $blogPass;
    }

    public function getBlogPass()
    {
        return $this->blogPass;
    }

    public function setBlogType($type)
    {
        $this->type = $type;
    }

    public function getBlogType()
    {
        return $this->blogType;
    }


}
