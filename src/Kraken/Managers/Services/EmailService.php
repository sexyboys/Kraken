<?php

namespace Kraken\Managers\Services;

use Kraken\Factories\DataFactory;
use Symfony\Bridge\Monolog\Logger;
/**
 * email service
 * Class EmailService
 * @package Kraken\Managers\Services
 */
class EmailService extends BaseService {

    const KEY_CONTENT = "[content]";

    const KEY_SOURCE = "[source]";

    protected $mailer;

    protected $from_email;

    protected $from_name;

    protected $displayLog;

    public function __construct(Logger $logger,$mailer,DisplayLogService $displayLog,$enable,$from_email,$from_name){

        $this->mailer = $mailer;
        $this->logger=$logger;
        $this->enable = $enable;
        $this->from_email = $from_email;
        $this->from_name = $from_name;
        $this->displayLog = $displayLog;
    }

    /**
     * Send email
     * @param $task TaskSenderEmail given
     * @param $content Data the content given
     * @param $source string the source
     * @return given content
     */
    public function prepare($task,$content,$source=null)
    {

        try{
            $emails= $task->getEmails();
            $object = $task->getObject();
            $adresses = array();
            for($i=1;$i<$emails->count();$i++)
            {
                $adresses[]=$emails->get($i);
            }
            if(DataFactory::getInstance()->isAnInstance($content,DataFactory::TYPE_LIST))
            {
                foreach($content->getContent() as $element)
                {
                    if(DataFactory::getInstance()->isAnInstance($element,DataFactory::TYPE_ARTICLE))
                    {
                        $str = $element->getTitle()." ".$element->getDate()->format('d-m-Y H:i:s')." ".$element->getContent();
                        $content_trans = str_replace(EmailService::KEY_CONTENT,$str,$task->getContent());
                        $content_trans = str_replace(EmailService::KEY_SOURCE,$source,$content_trans);
                        $this->send($object,$emails->first(),$adresses,$content_trans);
                    }
                    else{
                        $str = $element->getContent();
                        if($task->getContent()!=""){
                            $content_trans = str_replace(EmailService::KEY_CONTENT,$str,$task->getContent());
                            $content_trans = str_replace(EmailService::KEY_SOURCE,$source,$content_trans);
                        }
                        else $content_trans = $str;

                        $this->send($object,$emails->first(),$adresses,$content_trans);
                    }
                }
            }
            else if(DataFactory::getInstance()->isAnInstance($content,DataFactory::TYPE_ARTICLE))
            {

                $str = $content->getTitle()." ".$content->getDate()->format('d-m-Y H:i:s')." ".$content->getContent();
                if($task->getContent()!=""){
                    $content_trans = str_replace(EmailService::KEY_CONTENT,$str,$task->getContent());
                    $content_trans = str_replace(EmailService::KEY_SOURCE,$source,$content_trans);
                }
                else $content_trans = $str;
                $this->send($object,$emails->first(),$adresses,$content_trans);

            }
            else
            {

                $str = $content->getContent();
                if($task->getContent()!=""){
                    $content_trans = str_replace(EmailService::KEY_CONTENT,$str,$task->getContent());
                    $content_trans = str_replace(EmailService::KEY_SOURCE,$source,$content_trans);
                }
                else $content_trans = $str;
                $this->send($object,$emails->first(),$adresses,$content_trans);

            }
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

    /**Send content by email
     * @param $object string Email object
     * @param $email string
     * @param $adresses array of string
     * @param $content string
     */
    public function send($object,$email,$adresses,$content)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject($object)
            ->setFrom($this->from_email,$this->from_name)
            ->setTo($email)
            ->setCc($adresses)
            ->setBody(
                $content,"text/html"
            )
        ;
        $this->mailer->send($message);
        $this->displayLog->display('execute.display.email.send',array("%count%"=>(count($adresses)+1)),DisplayLogService::TYPE_SUCCESS);

        $this->logger->info("[EmailManager] Sending an email to ".(count($adresses)+1)." email(s)");
    }
}