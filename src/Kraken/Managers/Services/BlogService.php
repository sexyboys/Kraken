<?php

namespace Kraken\Managers\Services;

use Symfony\Bridge\Monolog\Logger;
/**
 * service on blog
 * Class BlogService
 * @package Kraken\Managers\Services
 */
class BlogService extends BaseService {

    public function __construct(Logger $logger,$enable){

        $this->logger=$logger;
        $this->enable = $enable;
    }
    /**
     * Send to blog
     * @param $login String
     * @param $pass String
     * @param $email
     * @param $link
     * @param $content
     * @param $source
     * @return given content
     */
    public function sendEmail($login,$pass,$email,$link,$content,$source=null)
    {
        return $content;
    }
}