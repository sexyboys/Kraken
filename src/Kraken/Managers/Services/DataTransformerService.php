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

/**
 * data transformer
 * Class DataTransformerService
 * @package Kraken\Managers\Services
 */
class DataTransformerService extends BaseService {

    public function __construct(Logger $logger=null){

        $this->logger=$logger;
        $this->enable = true;
    }

    /**
     * merge content into what its expected
     * @param $task Task
     * @param $returnType String the return type expected
     * @param $isReturnOut Integer is return type output
     * @param $content String
     * @param $title String
     * @param $date String
     * @return $result excepted
     */
    private function mergeContent($task,$returnType,$isReturnOut,$content,$title=null,$date=null)
    {
        $return = null;

        //if NONE return null
        if($returnType == DataFactory::getInstance()->getName(DataFactory::NONE))
        {
            $return = null;
        }
        else if($returnType == DataFactory::getInstance()->getName(DataFactory::SAME))
        {
            //if SAME recursive call with input
            if($isReturnOut){
                $return = $this->mergeContent($task,$task->getChosenInputData(),true,$content,$title,$date);
            }
            else{
                //get last task and output
                $previous = $task->getScenario()->getTasks()->get($task->getScenario()->getTasks()->indexOf($task)-1);
                $return = $this->mergeContent($previous,$previous->getChosenOutputData(),true,$content,$title,$date);
            }
        }
        else if($returnType == DataFactory::getInstance()->getName(DataFactory::TYPE_ARTICLE) || $returnType == DataFactory::getInstance()->getName(DataFactory::TYPE_LIST_ARTICLE) )
        {
            //if Article or List<Article> : create an article
            $return = new DataArticle();
            $return->setContent($content);
            $return->setDate(new \DateTime($date));
            $return->setTitle($title);
            $return->setSource($task->getLink());
        }
        else if($returnType == DataFactory::getInstance()->getName(DataFactory::TYPE_DATE))
        {
            //if DATE
            $return = new DataArticle();
            $return->setContent($content);
            $return->setDate(new \DateTime($date));
            $return->setTitle($title);
            $return->setSource($task->getLink());
        }
        else if($returnType == DataFactory::getInstance()->getName(DataFactory::TYPE_INTEGER))
        {
            //if integer
            $return = new DataInteger();
            $return->setContent($content);
        }
        else if($returnType == DataFactory::getInstance()->getName(DataFactory::TYPE_STRING) || $returnType == DataFactory::getInstance()->getName(DataFactory::TYPE_LIST_STRING) )
        {
            //if string or List string : create string
            $return = new DataString();
            $return->setContent($title." ".$date." ".$content);
        }

        return $return;
    }

    /**
     * Transform a data into what its expected
     * @param $data Data input content
     * @param $type Integer output index
     * @param $list DataList
     * @return Data transformed
     */
    public function transform($data,$type,$list=null)
    {

        $result = null;
        if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_ARTICLE))
        {
            $result = $this->transformArticle($data,$type,$list);
        }
        else if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_DATE))
        {
            $result = $this->transformDate($data,$type,$list);
        }
        else if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_INTEGER))
        {
            $result = $this->transformInteger($data,$type,$list);
        }
        else if(
            DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_LIST)
            || DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_LIST_ARTICLE)
            || DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_LIST_STRING)
        )
        {
            $result = $this->transformList($data,$type,$list);
        }
        else if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_STRING))
        {
            $result = $this->transformString($data,$type,$list);
        }

        return $result;
    }

    /**
     * Transform a data into what its expected
     * @param $data Data input content
     * @param $type Integer output index
     * @param $list DataList
     * @return Data transformed
     */
    public function transformArticle($data,$type,$list=null)
    {
        if($type == DataFactory::TYPE_LIST_ARTICLE)
        { //transform Article to a list of article

            //article added to the list if exist else create the list
            if($list==null)
            {
                $result = new DataList();
                $arr = new ArrayCollection();
                $arr->add($data);
                $result->setContent($arr);
            }
            else{
                $list->getContent()->add($data);
                $result = $list;
            }

        }
        else if($type == DataFactory::TYPE_STRING)
        {
            //article transformed into DataString
            $data_trans = new DataString();
            $data_trans->setContent($data->getTitle()." ".$data->getDate()->format('d-m-Y H:i:s')." ".$data->getContent());
            $result = $data_trans;
        }
        else if($type == DataFactory::TYPE_LIST_STRING)
        {
            //article transformed into array of string
            if($list == null)
            {
                $result = new DataList();
                $data_trans = new DataString();
                $data_trans->setContent($data->getTitle()." ".$data->getDate()->format('d-m-Y H:i:s')." ".$data->getContent());
                $arr = new ArrayCollection();
                $arr->add($data_trans);
                $result->setContent($arr);
            }
            else{
                $data_trans = new DataString();
                $data_trans->setContent($data->getTitle()." ".$data->getDate()->format('d-m-Y H:i:s')." ".$data->getContent());
                $list->getContent()->add($data_trans);
                $result = $list;
            }

        }
        else $result = $data;

        return $result;
    }

    /**
     * Transform a data into what its expected
     * @param $data Data input content
     * @param $type Integer output index
     * @param $list DataList
     * @return Data transformed
     */
    public function transformDate($data,$type,$list=null)
    {
       if($type == DataFactory::TYPE_STRING)
        {
            //date transformed into DataString
            $data_trans = new DataString();
            $data_trans->setContent($data->getContent()->format('d-m-Y H:i:s'));
            $result = $data_trans;
        }
        else $result = $data;

        return $result;
    }

    /**
     * Transform a data into what its expected
     * @param $data Data input content
     * @param $type Integer output index
     * @param $list DataList
     * @return Data transformed
     */
    public function transformInteger($data,$type,$list=null)
    {
        if($type == DataFactory::TYPE_STRING)
        {
            //date transformed into DataString
            $data_trans = new DataString();
            $data_trans->setContent($data->getContent());
            $result = $data_trans;
        }
        else $result = $data;

        return $result;
    }

    /**
     * Transform a data into what its expected
     * @param $data Data input content
     * @param $type Integer output index
     * @param $list DataList
     * @return Data transformed
     */
    public function transformList($data,$type,$list=null)
    {
        if($type == DataFactory::TYPE_LIST_STRING)
        {//Transform a list into a list of string
            //list transformed into LIST of string
            if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_LIST_ARTICLE))
            {
                //transform list of article into a list of string
                $result = $this->transformListArticleIntoListString($data,$list);
            }
            else{

                //transform list of string into the same
                if($list == "")
                {
                    $result = $data;
                }
                else
                {
                    //return list only
                    $result = $list;
                    //TODO MERGE LIST?
                }
            }

        }
        else if($type == DataFactory::TYPE_LIST_ARTICLE)
        {//Transform a list into a list of article
            //list transformed into LIST of article
            if(DataFactory::getInstance()->isAnInstance($data,DataFactory::TYPE_LIST_ARTICLE))
            {
                if($list == null) $result = $data;
                else $result = $list;
            }
            else{
                //transform list of string into list of article

                $result = $this->transformListStringIntoListArticle($data,$list);
            }
        }
        else
        {//Transform a list into a string
            //transform into other kind of list... same as list of string
            $str = "";
            foreach($data->getContent() as $row)
            {
                if(DataFactory::getInstance()->isAnInstance($row,DataFactory::TYPE_ARTICLE))
                {
                    $str.= $row->getTitle();
                    $str.= " \n ";
                    if($row->getDate()!=null)$str .= $row->getDate()->format('d-m-Y H:i:s');
                    $str.= " \n ";
                }
                $str.= $row->getContent();
                $str.= " \n ";
            }
            $result = new DataString();
            $result->setContent($str);
        }

        return $result;
    }

    /**
     * Transform a list of string into a list of article
     * @param $data DataList list of string
     * @param $list DataList list of string
     * @return DataList of DataArticle
     */
    public function transformListStringIntoListArticle($data,$list=null)
    {
        if($list == null) $list = new DataList();

        foreach($data->getContent() as $string)
        {
            $art = new DataArticle();
            $art->setContent($string);
            $list->getContent()->add($art);
        }

        return $list;

    }

    /**
     * Transform a list of article into a list of string
     * @param $data DataList list of article
     * @param $list DataList list of article
     * @return DataList of DataString
     */
    public function transformListArticleIntoListString($data,$list=null)
    {
        if($list == null) $list = new DataList();

        foreach($data->getContent() as $art)
        {
            $str = new DataString();
            $txt ="";
            if($art->getTitle()!="") $txt .= $art->getTitle()." // ";
            if($art->getDate()!=null) $txt .= $art->getDate()->format('d-m-Y H:i:s')." // ";
            if($art->getContent()!="") $txt .= $art->getContent();
            $str->setContent($txt);
            $list->getContent()->add($str);
        }

        return $list;

    }

    /**
     * Transform a data into what its expected
     * @param $data Data input content
     * @param $type Integer output index
     * @param $list DataList
     * @return Data transformed
     */
    public function transformString($data,$type,$list=null)
    {

        if($type == DataFactory::TYPE_LIST_ARTICLE)
        {//transform string into list of article
            //string added to the list if exist else create the list

            if($list==null)
            {
                $result = new DataList();
                $data_trans = new DataArticle();
                $data_trans->setContent($data->getContent());
                $arr = new ArrayCollection();
                $arr->add($data_trans);
                $result->setContent($arr);
            }
            else{
                $data_trans = new DataArticle();
                $data_trans->setContent($data->getContent());
                $list->getContent()->add($data_trans);
                $result = $list;
            }

        }
        else $result = $data;

        return $result;
    }

    /**
     * Purge Data to removed double (only for Output Data)
     * @param $task Task linked of those datas
     * @param $data Data returned by the recent task
     */
    public function purgeDoubles($task,$data)
    {
        try{
            //if one of the collection is empty merge them
            if($task->getOutputData() == null || $task->getOutputData()->getContent()==null || $data == null || $data->getContent() == null || $data->getContent()->count()==0)
            {
                $return = $data;
            }
            else if($data == null || $data->getContent()== null || $data->getContent()->count()==0 )
            {
                $return = $task->getOutputData();
            }
            else{

                //Find types of given datas
                if($task->getChosenOutputData() == DataFactory::getInstance()->getName(DataFactory::TYPE_LIST_ARTICLE))
                {//If DataListArticle
                    $return = new ArrayCollection();
                    //choose the Data with more content
                    if($task->getOutputData()->getContent()->count() >= $data->getContent()->count() )
                    {
                        foreach($task->getOutputData()->getContent() as $task_data)
                        {
                            $exist = false;
                            foreach($data->getContent() as $data_out)
                            {
                                //check if the row exist based on names for article
                                if($data_out->getName() == $task_data->getName())
                                {
                                    $exist = true;
                                }
                            }
                            if(!$exist) $return->add($task_data);
                        }

                    }
                    else{
                        foreach($data->getContent() as $data_out)
                        {
                            $exist = false;
                            foreach($task->getOutputData()->getContent() as $task_data)
                            {
                                //check if the row exist based on names for article
                                if($data_out->getName() == $task_data->getName())
                                {
                                    $exist = true;
                                }
                            }
                            if(!$exist) $return->add($data_out);
                        }
                    }
                }
                else
                {//if($task->getChosenOutputData() == DataFactory::getInstance()->getName(DataFactory::TYPE_LIST_STRING))
                //if DataList String,Integer,Date etc...
                   $return = new ArrayCollection();
                    //choose the Data with more content
                    if($task->getOutputData()->getContent()->count() >= $data->getContent()->count())
                    {
                        foreach($task->getOutputData()->getContent() as $task_data)
                        {
                            $exist = false;
                            foreach($data->getContent() as $data_out)
                            {
                                //check if the row exist based on the content for string
                                if($data_out->getContent() == $task_data->getContent())
                                {
                                    $exist = true;
                                }
                            }
                            if(!$exist) $return->add($task_data);
                        }
                    }
                    else{
                        foreach($data->getContent() as $data_out)
                        {
                            $exist = false;
                            foreach($task->getOutputData()->getContent() as $task_data)
                            {
                                //check if the row exist
                                if($data_out->getContent() == $task_data->getContent())
                                {
                                    $exist = true;
                                }
                            }
                            if(!$exist) $return->add($data_out);
                        }

                    }

                }
            }
        }
        catch(\Exception $exception)
        {
         throw $exception;
        }


        //replace the task data output
        $task->setOutputData($return);

        return $return;
    }
}