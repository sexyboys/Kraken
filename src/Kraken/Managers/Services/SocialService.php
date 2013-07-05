<?php

namespace Kraken\Managers\Services;

use Symfony\Bridge\Monolog\Logger;

/**
 * social service
 * Class WebCrawlerService
 * @package Kraken\Managers\Services
 */
class SocialService extends BaseService {

    public function __construct(Logger $logger,$enable){

        $this->logger=$logger;
        $this->enable = $enable;
    }

    /**
     * Send to social type
     * @param $content
     * @param $source
     * @return given content
     */
    public function sendEmail($content,$source=null)
    {
        return $content;
    }
}