<?php

namespace Kraken\Managers\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Inflexible\Inflexible;
use Kraken\UserBundle\Entity\DataArticle;
use Kraken\UserBundle\Entity\DataInteger;
use Kraken\UserBundle\Entity\DataList;
use Kraken\UserBundle\Entity\DataString;
use Kraken\Factories\DataFactory;
use Kraken\Factories\TagFactory;
use Symfony\Bridge\Monolog\Logger;
use Goutte\Client;
use Kraken\Exceptions\NoContentException;
use Kraken\Exceptions\ServiceDisableException;
use Kraken\Managers\Services\DisplayLogService;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Web service crawler using goutte
 * Class WebCrawlerService
 * @package Kraken\Managers\Services
 * @author epidoux
 * @version 1.0
 */
class WebCrawlerService extends BaseService {

    protected $displayLog;

    public function __construct(Logger $logger, DisplayLogService $displayLog,$enable){

        $this->logger=$logger;
        $this->displayLog=$displayLog;
        $this->enable = $enable;
    }

    /**
     * Crawl Extract web datas
     * @param $link String the link to extract
     * @param $multipageRegex String the link to multipage
     * @param $multipageLimit the last page to crawl
     * @param $linkMoreRegex the regex for link to more content
     * @param $tags the tags to use
     * @return The return result
     */
    public function extractData($link,$multipageRegex,$multipageLimit=1,$linkMoreRegex,$tags)
    {
        try{
            if(!$this->isAvailable()) throw new ServiceDisableException();
            $client = new Client();
            if($link==null) throw new \Exception(" Link is empty");
            $crawler = $client->request('GET', $link);
            $this->displayLog->display('execute.display.crawler.web.link',array("%link%"=>$link),DisplayLogService::TYPE_INFO);


            $result = new DataList();
            $result->setContent(new ArrayCollection());
            //handle multipage
            $this->displayLog->display('execute.display.crawler.web.multipage',array("%count%"=>$multipageLimit,"%regex%"=>$multipageRegex),DisplayLogService::TYPE_INFO);

            for($i=0;$i<$multipageLimit;$i++)
            {
                //if readmore
                if($linkMoreRegex!=null)
                {

                    $this->displayLog->display('execute.display.crawler.web.linkMore',array("%regex%"=>$linkMoreRegex),DisplayLogService::TYPE_INFO);

                    $mother = TagFactory::getInstance()->getTag($tags,TagFactory::TYPE_MOTHER);
                    if($mother!=null)
                    {

                        $this->displayLog->display('execute.display.crawler.web.tags.mother',array("%regex%"=>$mother->getRegex()),DisplayLogService::TYPE_INFO);

                        $j=0;
                        $break=false;
                        while(!$break)
                        {

                            $node = $crawler->filter($mother->getRegex())->eq($j);

                            if( $j == count($crawler->filter($mother->getRegex()))-1){
                                //last loop
                                $break=true;
                            }
                            try
                            {
                                $read_link =  $node->filter($linkMoreRegex)->link();

                                $crawl = $this->crawl($tags,$client->click($read_link),$read_link->getUri());

                                $this->logger->info("Extracting data : ".Inflexible::shortenString($crawl->getTitle()));
                                $this->displayLog->display('execute.display.crawler.web.found',array("%title%"=>$crawl->getTitle()),DisplayLogService::TYPE_SUCCESS);

                                $result->getContent()->add($crawl);

                                $j++;
                            }
                            catch(\Exception $e){
                                //exception catch beacause no readmore link for this article...
                                $crawl =  $this->crawl($tags,$node,$link);
                                $this->logger->info("Extracting data without readmore article: ".Inflexible::shortenString($crawl->getTitle()));
                                $this->displayLog->display('execute.display.crawler.web.found',array("%title%"=>Inflexible::shortenString($crawl->getTitle())),DisplayLogService::TYPE_SUCCESS);

                                $result->getContent()->add($crawl);

                                $j++;
                            }


                        }

                    }
                    else{
                        //don't parse here but forward

                        try
                        {
                            $read_link =  $crawler->filter($linkMoreRegex)->link();
                            $crawl = $this->crawl($tags,$client->click($read_link),$read_link->getUri());
                            $this->logger->info("Extracting data : ".Inflexible::shortenString($crawl->getTitle()));
                            $this->displayLog->display('execute.display.crawler.web.found',array("%title%"=>Inflexible::shortenString($crawl->getTitle())),DisplayLogService::TYPE_SUCCESS);

                            $result->getContent()->add($crawl);
                        }
                        catch(\Exception $e){
                            //no readmore link
                            $crawl =  $this->crawl($tags,$crawler,$link);
                            $this->logger->info("Extracting data : ".Inflexible::shortenString($crawl->getTitle()));
                            $this->displayLog->display('execute.display.crawler.web.found',array("%title%"=>Inflexible::shortenString($crawl->getTitle())),DisplayLogService::TYPE_SUCCESS);

                            $result->getContent()->add($crawl);
                        }



                    }
                }
                else{//no readmore

                    //check if there is a mother
                    $mother = TagFactory::getInstance()->getTag($tags,TagFactory::TYPE_MOTHER);
                    if($mother!=null)
                    {
                        //a mother is set
                        $j=0;
                        $break=false;
                        while(!$break)
                        {

                            $node = $crawler->filter($mother->getRegex())->eq($j);

                            if( $j == count($crawler->filter($mother->getRegex()))-1){
                                //last loop
                                $break=true;
                            }

                            $crawl =  $this->crawl($tags,$node,$link);
                            $this->logger->info("Extracting data : ".Inflexible::shortenString($crawl->getTitle()));
                            $this->displayLog->display('execute.display.crawler.web.found',array("%title%"=>Inflexible::shortenString($crawl->getTitle())),DisplayLogService::TYPE_SUCCESS);

                            $result->getContent()->add($crawl);

                            $j++;



                        }

                    }
                    else{//no mother tag
                        //extract content one by one
                        $crawl = $this->crawl($tags,$crawler,$link);
                        $this->logger->info("Extracting data : ".Inflexible::shortenString($crawl->getTitle()));
                        $this->displayLog->display('execute.display.crawler.web.found',array("%title%"=>Inflexible::shortenString($crawl->getTitle())),DisplayLogService::TYPE_SUCCESS);

                        $result->getContent()->add($crawl);
                    }
                }

                //go to the next page
                if($multipageRegex!=null)
                {
                    $link = $crawler->filter($multipageRegex)->link();

                    $crawler = $client->click($link);
                }
            }
        }
        catch(\Exception $e)
        {
            $this->logger->err("[WebCrawlerService] Error while extracting data : ".$e->getMessage()." : ".$e->getTraceAsString());
            throw $e;

        }

        return $result;


    }

    /**
     * crawl nodes
     * @param $tags ArrayCollection
     * @param $crawler DomCrawler the crawler
     * @param $link string the link
     * @return $result
     */
    public function crawl($tags=null,$crawler,$link)
    {
        try{
            $result = null;
            $date = null;
            if($tags!=null && count($tags)>0)
            {

                //get other tag
                $tag = TagFactory::getInstance()->getTag($tags,TagFactory::TYPE_TITLE);
                if($tag != null)
                {
                    $filterTitle = $tag->getRegex();

                    if($filterTitle != null)
                    {
                        $this->displayLog->display('execute.display.crawler.web.tags.title',array("%regex%"=>$tag->getRegex()),DisplayLogService::TYPE_INFO);

                        $title = $crawler->filter($filterTitle)->html();
                    }
                }

                $tag = TagFactory::getInstance()->getTag($tags,TagFactory::TYPE_DATE);
                if($tag != null)
                {
                    $filterDate = $tag->getRegex();

                    try{
                        if($filterDate != null)
                        {
                            $this->displayLog->display('execute.display.crawler.web.tags.date',array("%regex%"=>$tag->getRegex()),DisplayLogService::TYPE_INFO);

                            $date = new \DateTime($crawler->filter($filterDate)->text());
                        }
                    }
                    catch(\Exception $e){}//error while parsing date
                }

                $tag = TagFactory::getInstance()->getTag($tags,TagFactory::TYPE_CONTENT);
                if($tag!=null)
                {
                    $filterContent = $tag->getRegex();
                    if($filterContent != null)
                    {
                        $this->displayLog->display('execute.display.crawler.web.tags.content',array("%regex%"=>$tag->getRegex()),DisplayLogService::TYPE_INFO);

                        $content = $crawler->filter($filterContent)->html();
                    }
                }

                if($content=="" && $title=="" && $date=="") throw new NoContentException();

                //merge content into  Article
                $result = new DataArticle();
                $result->setContent(html_entity_decode($content,ENT_QUOTES,"utf-8"));
                $result->setTitle(html_entity_decode($title,ENT_QUOTES,"utf-8"));
                $result->setDate($date);
                $result->setSource($link);


            }
            else{
                //return crawler convert to text
                $content = $crawler->filter()->text();
                $result = new DataString();
                $result->setContent(html_entity_decode($content,ENT_QUOTES,"utf-8"));

            }
        }
        catch(\Exception $e)
        {
            $this->logger->err("[WebCrawlerService][crawl] Error while crawling data :".$e->getCode()." : ".$e->getMessage());

            //throw $e;
        }

        return $result;
    }

    /**
     * Make a fast json call and get json object
     * @param $link string the link
     * @return Json Object
     */
    public function jsonCall($link)
    {
        // Initializing curl %7C
        $ch = curl_init( $link );
        // Configuring curl options
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json')
        );

        curl_setopt_array( $ch, $options );

        $json =  curl_exec($ch); // Getting jSON result string

        $json = json_decode($json);

        return $json;
    }


}