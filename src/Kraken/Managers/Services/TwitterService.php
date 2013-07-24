<?php

namespace Kraken\Managers\Services;

use epidoux\TwitterBundle\Twitter\Client\HTTP;
use epidoux\TwitterBundle\Twitter\Api;
use epidoux\TwitterBundle\Twitter\Api\Statuses;
use epidoux\TwitterBundle\Twitter\Client\OAuth;

/**
 * Class TwitterService
 * @package Kraken\Managers\Services
 * @author epidoux
 * @version 1.0
 */
class TwitterService {

    /** Prepare the data to send
     * @param $login
     * @param $pass
     * @param $type
     */
    public function tweet($login,$pass,$msg)
    {
        $client = new HTTP("eric_pidoux", "v4!h4ll4");
        //$client = new OAuth('qRfVen9Rcy5T7y1BOuigA', '6RsktJUcIFU8myfFM0iv73z7qlBqFqALVE3XoKnls');

        $statuses = new Statuses($client);
        print_r($statuses);exit;


        $api = new Api($client);
        //print_r($api->);exit;
        $results = $api->post('statuses/update', array('status' => $msg));
        print_r($results);exit;
    }


}