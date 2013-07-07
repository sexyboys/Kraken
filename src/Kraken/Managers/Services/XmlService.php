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
use Kraken\UserBundle\Entity\Data;
use Symfony\Component\DomCrawler\Crawler;
use Kraken\Managers\Services\DisplayLogService;
use Kraken\Exceptions\NoContentFoundException;
use Kraken\Exceptions\XSLTParseErrorException;

/**
 * xml transformer
 * Class XmlService
 * @package Kraken\Managers\Services
 */
class XmlService extends BaseService {

    protected $displayLog;

    public function __construct(Logger $logger=null, DisplayLogService $displayLog){

        $this->logger=$logger;
        $this->enable = true;
        $this->displayLog=$displayLog;
    }


    /**
     * Transform Data into Xml
     * @param $task TaskActionArranger
     * @param $datas Data given to transform
     * @return DataString (xml)
     */
    public function transformDataToXml($task,$datas)
    {
        try
        {
            $xml="";

            if(DataFactory::getInstance()->isAnInstance($datas,DataFactory::TYPE_LIST_STRING)
              || DataFactory::getInstance()->isAnInstance($datas,DataFactory::TYPE_LIST)
              || DataFactory::getInstance()->isAnInstance($datas,DataFactory::TYPE_LIST_ARTICLE)
            )
            {//list element
                if($task->getSeparateList())$result = new DataList();
                else $result = new DataString();

                $xml_front = '<?xml version="1.0" encoding="ISO-8859-1"?>
                    <root><datas>';

                $xml_end='</datas></root>';

                foreach($datas->getContent() as $data)
                {

                    $xml.='<data type="'.DataFactory::getInstance()->getDataName($data).'">';

                    if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_ARTICLE))
                    {
                        $xml.='<title>'.$data->getTitle()."</title>";
                        $xml.='<date>'.$data->getDate()."</date>";
                    }

                    $xml.='<content>'.$data->getContent()."</content>";

                    $xml.='</data>';

                    if($task->getSeparateList())
                    {
                        //add it
                        $el = new DataString();
                        $el->setContent($xml_front.$xml.$xml_end);
                        $result->getContent()->add($el);
                        $xml = "";

                    }
                }
            }
            else{//single element

                $xml.='<data type="'.DataFactory::getInstance()->getDataName($datas).'">';

                if(DataFactory::getInstance()->isAnInstance($datas,DataFactory::TYPE_ARTICLE))
                {
                    $xml.='<title>'.$datas->getTitle()."</title>";
                    $xml.='<date>'.$datas->getDate()."</date>";
                }

                $xml.='<content>'.$datas->getContent()."</content>";

                $xml.='</data>';

            }

            if(!$task->getSeparateList()){
                $result->setContent($xml_front.$xml.$xml_end);
            }

            $this->displayLog->display('execute.display.xml',array(),DisplayLogService::TYPE_INFO);
        }
        catch(\Exception $e)
        {
            $this->logger->err("[XmlService] Error while transforming Data into xml : ".$e->getMessage());

            $msg = explode("/",$e->getMessage());
            $this->displayLog->display('execute.display.xml.error',array("%msg%"=>$msg[0]),DisplayLogService::TYPE_ERROR);
            throw $e;
        }

        return $result;
    }


    /**
     * Transform DataString(Xml) given into string with xslt
     * @param $xslt string the xslt used to transform
     * @param $xml Data the xml to transform
     * @return the Data transformed
     */
    public function transformXmlWithXslt($xslt,$dataxml)
    {
        try
        {
            $this->displayLog->display('execute.display.xml.xslt',array(),DisplayLogService::TYPE_INFO);

            $xsl = new \DomDocument();
            $xsl->loadXML($xslt, LIBXML_NOCDATA);

            if(DataFactory::getInstance()->isAnInstance($dataxml,DataFactory::TYPE_LIST_STRING)
                || DataFactory::getInstance()->isAnInstance($dataxml,DataFactory::TYPE_LIST)
                || DataFactory::getInstance()->isAnInstance($dataxml,DataFactory::TYPE_LIST_ARTICLE)
            )
            {//list data
                foreach($dataxml as $el)
                {
                    $proc = new \XSLTProcessor();
                    $proc->importStylesheet($xsl);

                    $xml = new \DomDocument();
                    $xml->loadXML($el->getContent());
                    $resultFile = $proc->transformToDoc($xml);
                    $result = $resultFile->saveHTML();
                    $el->setContent(html_entity_decode($result));
                    if(!$result) throw new XSLTParseErrorException("Error in your XSLT file, please check it first");
                }
            }
            else{
                //data string

                $proc = new \XSLTProcessor();
                $proc->importStylesheet($xsl);
                $xml = new \DomDocument();
                $xml->loadXML($dataxml->getContent());
                $resultFile = $proc->transformToDoc($xml);
                $result = $resultFile->saveHTML();
                if(!$result) throw new XSLTParseErrorException("Error in your XSLT file, please check it first");

                $dataxml->setContent(html_entity_decode($result));
            }

        }
        catch(\Exception $e)
        {
            $this->logger->err("[XmlService] Error while transforming Xml with xslt : ".$e->getMessage());
            $msg = explode("/",$e->getMessage());
            $res = $msg[0];
            $this->displayLog->display('execute.display.xml.xslt.error',array("%msg%"=>$res),DisplayLogService::TYPE_ERROR);

            throw $e;
        }

        return $dataxml;
    }
}