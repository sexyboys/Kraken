<?php

namespace Kraken\Managers\Services;

use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * handle the display of log when user execute task
 * Class DisplayLogService
 * @package Kraken\Managers\Services
 */
class DisplayLogService extends BaseService {

    const TYPE_INFO = 0;

    const TYPE_SUCCESS = 1;

    const TYPE_ERROR = 2;

    private $result;

    public function __construct(Logger $logger,Translator $trans){

        $this->logger=$logger;
        $this->translator = $trans;
        $this->enable = true;
    }
    /**
     * display
     * @param $msg String the msg code to translate
     * @param $params array of parameters to translation
     * @param $type integer the msg type
     * @param $e Exception given
     */
    public function display($msg,$params=null,$type=0,$e=null)
    {

        $content = $this->translator->trans($msg,$params);
        if($e!=null){
            $exception_msg = explode("/",$e->getMessage());
            $content.= " : ".$exception_msg[0];
        }
        $this->result[]=array($type,$content);
    }

    /**
     * Get the logs
     * @return array of logs
     */
    public function getLog()
    {
        return $this->result;
    }

    /**
     * Clear datas
     */
    public function clear()
    {
        $this->result = null;
    }
}