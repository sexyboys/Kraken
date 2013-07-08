<?php

namespace Kraken\Managers\Services;

use Symfony\Bridge\Monolog\Logger;
use Kraken\Managers\Services\EmailService;
use Kraken\Factories\DataFactory;

/**
 * service on blog
 * Class BlogService
 * @package Kraken\Managers\Services
 * @author epidoux
 * @version 1.0
 */
class BlogService extends BaseService
{

    const TYPE_WORDPRESS = "blog.wordpress";

    const TYPE_GOOGLE = "blog.google";

    const TYPE_TRUMBLR = "blog.trumblr";

    private $emailService;

    private $translator;

    private $displayLog;

    public function __construct(Logger $logger,EmailService $emailService,$translator,$displayLog, $enable){

        $this->logger=$logger;
        $this->enable = $enable;
        $this->emailService = $emailService;
        $this->translator = $translator;
        $this->displayLog = $displayLog;
    }

    /**
     * Prepare content to send to blog
     * @param $task Task
     * @param $content Data
     * @param $source String the source
     * @return Data content
     */
    public function prepare($task,$content,$source=null)
    {
        try{
            $count=0;
            if(DataFactory::getInstance()->isAnInstance($content,DataFactory::TYPE_LIST))
            {
                foreach($content->getContent() as $element)
                {
                    if(DataFactory::getInstance()->isAnInstance($element,DataFactory::TYPE_ARTICLE))
                    {
                        $str = $element->getDate()->format('d-m-Y H:i:s')." ".$element->getContent();
                        $this->send($task,$str,$element->getTitle());
                    }
                    else{
                        $str = $element->getContent();
                        $this->send($task,$str);
                    }
                    $count++;
                }
            }
            else if(DataFactory::getInstance()->isAnInstance($content,DataFactory::TYPE_ARTICLE))
            {

                $str = $content->getDate()->format('d-m-Y H:i:s')." ".$content->getContent();
                $this->send($task,$str,$content->getTitle());

                $count++;
            }
            else
            {

                $str = $content->getContent();
                $this->send($task,$str);
                $count++;

            }
            $this->displayLog->display('execute.display.blog.send',array("%count%"=>$count,"%blog%"=>$this->translator->trans($task->getBlogType())),DisplayLogService::TYPE_INFO);

        }
        catch(\Exception $e)
        {
            $this->logger->err("Error while sending mail '".$object."' to ".count($emails)." : ".$e->getCode()." ".$e->getMessage());

            $msg = explode("/",$e->getMessage());
            $res = $msg[0];
            $this->displayLog->display('execute.display.email.error',array("%msg%"=>$res),DisplayLogService::TYPE_ERROR);
        }
        return $content;
    }

    /**
     * Send content to the blog using link and login/pass or by email
     * @param TaskSenderBlog task
     * @param String $content
     */
    public function send($task,$content,$title=null)
    {
        if($task->getBlogType() == self::TYPE_WORDPRESS)
        {
            //if there is an email defined send to the email and crawl the link
            if($task->getBlogLogin()!="" && $task->getBlogPass()!="")
            {
                //send xmlrpc request
                $content_params = array(
                    'title' => html_entity_decode(htmlentities($title,ENT_COMPAT,'UTF-8'),ENT_COMPAT,'ISO-8859-1'),
                    'description' => html_entity_decode(htmlentities($content,ENT_COMPAT,'UTF-8'),ENT_COMPAT,'ISO-8859-1'),
                    'mt_allow_comments' => 0,  // 1 to allow comments
                    'mt_allow_pings' => 0,  // 1 to allow trackbacks
                    'post_type' => 'post'
                );
                $params = array(0,$task->getBlogLogin(),$task->getBlogPass(),$content_params,false);

                $this->send_request('metaWeblog.newPost',$params,$task->getBlogLink());
            }
            else{

                $this->emailService->send(
                    $title!=null?$title:$this->translator->trans('service.blog.email.object'),
                    $task->getBlogEmail(),
                    null,
                    $content
                );

                //if there is a link, crawl it to crawl email
                //$this->emai
            }
        }
        return true;

    }

    /**
     * Send the xmlrpc request
     * @param $requestname
     * @param $params
     * @param $url
     * @return mixed
     */
    function send_request($requestname, $params,$url)
    {
        $request = xmlrpc_encode_request($requestname, $params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $results = curl_exec($ch);
        curl_close($ch);
        return $results;
    }

}