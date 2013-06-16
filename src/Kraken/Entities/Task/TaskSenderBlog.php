<?php
namespace Kraken\Entities\Task;

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
     * @ORM\Column(name="blog_login",type="string",nullable=true)
     */
    protected $blogLogin;

    /**
     * Blog user pass
     * @ORM\Column(name="blog_pass",type="string",nullable=true)
     */
    protected $blogPass;

    /**
     * Blog email
     * @ORM\Column(name="blog_email",type="string",nullable=true)
     */
    protected $blogEmail;

    /**
     * Blog link example with wordpress(link)/xml-rpc.php
     * @ORM\Column(name="blog_link",type="string",nullable=true)
     */
    protected $blogLink;
}
