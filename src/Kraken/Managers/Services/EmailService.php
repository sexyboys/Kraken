<?php

namespace Kraken\Managers\Services;

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

    public function __construct(Logger $logger,$mailer,$enable,$from_email,$from_name){

        $this->mailer = $mailer;
        $this->logger=$logger;
        $this->enable = $enable;
        $this->from_email = $from_email;
        $this->from_name = $from_name;
    }

    /**
     * Send email
     * @param $emails ArrayCollection of email
     * @param $object String the object
     * @param $content
     * @param $source
     * @return given content
     */
    public function send($emails,$object,$content,$source=null)
    {
        try{
            $adresses = array();
            for($i=1;$i<$emails->count();$i++)
            {
                $adresses[]=$emails->get($i);
            }

            $message = \Swift_Message::newInstance()
                ->setSubject($object)
                ->setFrom($this->from_email,$this->from_name)
                ->setTo($emails->first())
                ->setCc($adresses)
                ->setBody(
                    $content,"text/html"
                )
            ;
            $this->mailer->send($message);
            $this->logger->info("[EmailManager] Sending an email to ".count($emails)." email(s)");
        }
        catch(\Exception $e)
        {
            $this->logger->err("Error while sending mail '".$object."' to ".count($emails)." : ".$e->getCode()." ".$e->getMessage());
        }
        return $content;
    }
}