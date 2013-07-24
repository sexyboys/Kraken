<?php

namespace Kraken\Managers\Services;

use Symfony\Bridge\Monolog\Logger;
<<<<<<< HEAD
use Kraken\Factories\DataFactory;
use Kraken\Managers\Services\TwitterService;
=======
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883

/**
 * social service
 * Class WebCrawlerService
 * @package Kraken\Managers\Services
 */
class SocialService extends BaseService {

<<<<<<< HEAD
    const TYPE_TWITTER = "social.twitter";

    const TYPE_FACEBOOK = "social.facebook";

    const TYPE_GOOGLE = "social.google";

    private $twitterService;

    public function __construct(Logger $logger,$enable,TwitterService $twitterService){

        $this->logger=$logger;
        $this->enable = $enable;
        $this->twitterService= $twitterService;
=======
    public function __construct(Logger $logger,$enable){

        $this->logger=$logger;
        $this->enable = $enable;
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883
    }

    /**
     * Send to social type
<<<<<<< HEAD
     * @param $task SenderSocialTask given task
     * @param $content Data
     * @param $source
     * @return given content
     */
    public function prepare($task,$content,$source=null)
    {
        if(DataFactory::getInstance()->isAnInstance($content,DataFactory::TYPE_LIST_ARTICLE))
        {
            foreach($content as $data)
            {
                $msg = $data->getTitle()." \n".$data->getDate()->format('d-m-Y H:i:s')." \n".$data->getContent();
                $code = $this->oauth->request('POST', $this->oauth->url('1/statuses/update'), array(
                    'status' =>  utf8_encode($msg)
                ));
                print_r($code);exit;
            }

        }
        else if(DataFactory::getInstance()->isAnInstance($content,DataFactory::TYPE_LIST_STRING))
        {
            foreach($content as $data)
            {
                $msg = $data->getContent();
                $code = $this->oauth->request('POST', $this->oauth->url('1/statuses/update'), array(
                    'status' =>  utf8_encode($msg)
                ));
                print_r($code);exit;
            }

        }
        else if(DataFactory::getInstance()->isAnInstance($content,DataFactory::TYPE_ARTICLE))
        {
            $msg = $content->getTitle()." \n".$content->getDate()->format('d-m-Y H:i:s')." \n".$content->getContent();
            $code = $this->oauth->request('POST', $this->oauth->url('statuses/update'), array(
                    'status' =>  utf8_encode($msg)
                ));
                print_r($code);exit;


        }
        else if(DataFactory::getInstance()->isAnInstance($content,DataFactory::TYPE_STRING))
        {

            $this->twitterService->tweet($task->getLogin(),$task->getPass(),$content->getContent());

            //$content = $this->twitterApi->get('account/verify_credentials');
            $this->twitterApi->request("POST", $this->twitterApi->url("oauth/request_token", ""), array(
                // pass a variable to set the callback
                'oauth_callback'    => $this->twitterApi->php_self()
            ));
            print_r($this->twitterApi->response);exit;
            echo $this->twitterApi->response["code"];exit;


                $res = $this->twitterApi->post('statuses/update', array('status' => 'test'));

            print_r($res);exit;
            $response =  $twitter->buildOauth($url, $requestMethod)
                ->setPostfields($postfields)
                ->performRequest();

            var_dump(json_decode($response));exit;

            echo $this->oauth->user_request();exit;
            $code = $this->oauth->request('POST', $this->oauth->url('1.1/statuses/update'), array(
                'status' =>  utf8_encode($msg)
            ));
            print_r($code);exit;

        }

        //print_r($code);
        //print_r($tmhOAuth);

        if ($code == 200) {
            //  tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
            return TRUE;
        } else {
            //  tmhUtilities::pr($tmhOAuth->response['response']);
            return FALSE;
        }


        $value = $this->oauth->tweet(' TEST AUTO ');
        echo $value;
=======
     * @param $content
     * @param $source
     * @return given content
     */
    public function sendEmail($content,$source=null)
    {
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883
        return $content;
    }
}