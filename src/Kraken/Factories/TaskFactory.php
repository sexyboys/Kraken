<?php

namespace Kraken\Factories;

use Doctrine\Common\Collections\ArrayCollection;
use Inflexible\Inflexible;
use Kraken\AdminBundle\Form\Type\TaskActionTranslateType;
use Kraken\AdminBundle\Form\Type\TaskActionArrangerTextType;
use Kraken\AdminBundle\Form\Type\TaskCrawlWebType;
use Kraken\AdminBundle\Form\Type\TaskSenderBlogType;
use Kraken\AdminBundle\Form\Type\TaskSenderEmailType;
use Kraken\AdminBundle\Form\Type\TaskSenderSocialType;
use Kraken\UserBundle\Entity\TaskActionArrangerText;
use Kraken\UserBundle\Entity\TaskActionTranslate;
use Kraken\UserBundle\Entity\TaskCrawlWeb;
use Kraken\UserBundle\Entity\TaskSenderBlog;
use Kraken\UserBundle\Entity\TaskSenderEmail;
use Kraken\UserBundle\Entity\TaskSenderSocial;

/**
 * Class TaskFactory
 * @package Kraken\Factories
 * @author Eric Pidoux
 * @version 1.0
 */
class TaskFactory {

    const TASK_CRAWL_WEB=0;

    const TASK_ACTION_TRANSLATE=1;

    const TASK_SENDER_EMAIL=2;

    const TASK_SENDER_BLOG=3;

    const TASK_SENDER_SOCIAL=4;

    const TASK_ACTION_ARRANGER_TEXT=5;


    const TASK_CRAWL_WEB_CLASSNAME="TaskCrawlWeb";

    const TASK_ACTION_TRANSLATE_CLASSNAME="TaskActionTranslate";

    const TASK_ACTION_ARRANGER_TEXT_CLASSNAME="TaskActionArrangerText";

    const TASK_SENDER_EMAIL_CLASSNAME="TaskSenderEmail";

    const TASK_SENDER_BLOG_CLASSNAME="TaskSenderBlog";

    const TASK_SENDER_SOCIAL_CLASSNAME="TaskSenderSocial";

    /**
     * @var Instance of the Factory
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * ArrayCollection of task types
     */
    private $types;

    /**
     * Constructor
     *
     */
    private function __construct() {

    }

    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new TaskFactory();
        }
        return self::$_instance;
    }

    /**
     * Load types of tasks
     * @return array of types tasks
     */
    public function loadTypes()
    {
        if($this->types==null){
            $this->types = new ArrayCollection();
            $this->types->add(Inflexible::denamespace(get_class(new TaskCrawlWeb())));
            $this->types->add(Inflexible::denamespace(get_class(new TaskActionTranslate())));
            $this->types->add(Inflexible::denamespace(get_class(new TaskSenderEmail())));
            $this->types->add(Inflexible::denamespace(get_class(new TaskSenderBlog())));
            $this->types->add(Inflexible::denamespace(get_class(new TaskSenderSocial())));
            $this->types->add(Inflexible::denamespace(get_class(new TaskActionArrangerText())));
        }
        return $this->types;
    }


    /**
     * Load entity from types
     * @param index of tasks array
     * @return instance of tasks
     */
    public function getTypeInstance($index)
    {
        switch($index)
        {
            case 0:
                $instance = new TaskCrawlWeb();break;
            case 1:
                $instance = new TaskActionTranslate();break;
            case 2:
                $instance = new TaskSenderEmail();break;
            case 3:
                $instance = new TaskSenderBlog();break;
            case 4:
                $instance = new TaskSenderSocial();break;
            case 5:
                $instance = new TaskActionArrangerText();break;
        }
        return $instance;
    }

    /**
     * Load entity from types
     * @param integer $index of tasks array
     * @param object $params the params to give to the form type
     * @return instance of tasks
     */
    public function getTypeFormInstance($index,$params=null)
    {
        switch($index)
        {
            case 0:
                $instance = new TaskCrawlWebType();break;
            case 1:
                $instance = new TaskActionTranslateType();break;
            case 2:
                $instance = new TaskSenderEmailType();break;
            case 3:
                $instance = new TaskSenderBlogType($params);break;
            case 4:
                $instance = new TaskSenderSocialType();break;
            case 5:
                $instance = new TaskActionArrangerTextType();break;
        }
        return $instance;
    }

    /**
     * Find index from type name
     * @param classname of task not translated
     * @return index
     */
    public function getTypeIndex($name)
    {
        if($this->types == null )$this->loadTypes();
        return $this->types->indexOf($name);
    }

    /**
     * Generate array of Task type -> datas arraycollection
     * @return array of Task: datas arraycollection
     */
    public function getTaskTypesWithDatas()
    {
        $array = array();
        $array[self::TASK_CRAWL_WEB_CLASSNAME]['in'] = $this->defineArrayDataByTask(self::TASK_CRAWL_WEB,true);
        $array[self::TASK_CRAWL_WEB_CLASSNAME]['out'] = $this->defineArrayDataByTask(self::TASK_CRAWL_WEB,false);

        $array[self::TASK_ACTION_TRANSLATE_CLASSNAME]['in'] = $this->defineArrayDataByTask(self::TASK_ACTION_TRANSLATE,true);
        $array[self::TASK_ACTION_TRANSLATE_CLASSNAME]['out'] = $this->defineArrayDataByTask(self::TASK_ACTION_TRANSLATE,false);

        $array[self::TASK_SENDER_EMAIL_CLASSNAME]['in'] = $this->defineArrayDataByTask(self::TASK_SENDER_EMAIL,true);
        $array[self::TASK_SENDER_EMAIL_CLASSNAME]['out'] = $this->defineArrayDataByTask(self::TASK_SENDER_EMAIL,false);

        $array[self::TASK_SENDER_BLOG_CLASSNAME]['in'] = $this->defineArrayDataByTask(self::TASK_SENDER_BLOG,true);
        $array[self::TASK_SENDER_BLOG_CLASSNAME]['out'] = $this->defineArrayDataByTask(self::TASK_SENDER_BLOG,false);

        $array[self::TASK_SENDER_SOCIAL_CLASSNAME]['in'] = $this->defineArrayDataByTask(self::TASK_SENDER_SOCIAL,true);
        $array[self::TASK_SENDER_SOCIAL_CLASSNAME]['out'] = $this->defineArrayDataByTask(self::TASK_SENDER_SOCIAL,false);

        $array[self::TASK_ACTION_ARRANGER_TEXT_CLASSNAME]['in'] = $this->defineArrayDataByTask(self::TASK_ACTION_ARRANGER_TEXT,true);
        $array[self::TASK_ACTION_ARRANGER_TEXT_CLASSNAME]['out'] = $this->defineArrayDataByTask(self::TASK_ACTION_ARRANGER_TEXT,false);

        return $array;

    }

    /**
     * Define data in & out for all tasks
     * @param index the task index
     * @param in boolean is data in?
     * @return return data array (NOT ARRAYCOLLECTION)
     */
    public function defineArrayDataByTask($index,$in)
    {
        $array = array();
        switch($index)
        {
            case self::TASK_CRAWL_WEB_CLASSNAME:
                //TaskCrawlWeb
                if($in){
                    //in
                    $array[]=DataFactory::NONE;
                }
                else{
                    //out
                    $array[]=DataFactory::TYPE_ARTICLE;
                    $array[]=DataFactory::TYPE_STRING;
                    $array[]=DataFactory::TYPE_LIST_STRING;
                    $array[]=DataFactory::TYPE_LIST_ARTICLE;
                }
                break;
            case self::TASK_ACTION_TRANSLATE_CLASSNAME:
                //TaskActionTranslate
                if($in){
                    //in
                    $array[]=DataFactory::TYPE_ARTICLE;
                    $array[]=DataFactory::TYPE_STRING;
                    $array[]=DataFactory::TYPE_LIST_STRING;
                    $array[]=DataFactory::TYPE_LIST_ARTICLE;
                }
                else{
                    //out same as in

                    $array[]=DataFactory::SAME;
                }
                break;
            case self::TASK_SENDER_EMAIL_CLASSNAME:
                //TaskSenderEmail
                if($in){
                    //in
                    $array[]=DataFactory::SAME;
                }
                else{
                    //out
                    $array[]=DataFactory::SAME;
                }
                break;
            case self::TASK_SENDER_BLOG_CLASSNAME:
                //TaskSenderBlog
                if($in){
                    //in
                    $array[]=DataFactory::SAME;
                }
                else{
                    //out
                    $array[]=DataFactory::SAME;
                }
                break;
            case self::TASK_SENDER_SOCIAL_CLASSNAME:
                //TaskSenderSocial
                if($in){
                    //in
                    $array[]=DataFactory::SAME;
                }
                else{
                    //out
                    $array[]=DataFactory::SAME;
                }
                break;
            case self::TASK_ACTION_ARRANGER_TEXT_CLASSNAME:
                //TaskActionArrangerText
                if($in){
                    //in
                    $array[]=DataFactory::SAME;
                }
                else{
                    //out
                    $array[]=DataFactory::SAME;
                }
                break;
        }

        return $array;
    }

    /**
     * Define data in & out for all tasks
     * @param index the task index
     * @param in boolean is data in?
     * @return return data array
     */
    public function defineDataByTask($index,$in)
    {
        $array = new ArrayCollection();
        switch($index)
        {
            case self::TASK_CRAWL_WEB:
                //TaskCrawlWeb
                if($in){
                    //in
                    $array->add(DataFactory::NONE);
                }
                else{
                    //out
                    $array->add(DataFactory::TYPE_ARTICLE);
                    $array->add(DataFactory::TYPE_STRING);
                    $array->add(DataFactory::TYPE_LIST_STRING);
                    $array->add(DataFactory::TYPE_LIST_ARTICLE);
                }
                break;
            case self::TASK_ACTION_TRANSLATE:
                //TaskActionTranslate
                if($in){
                    //in
                    $array->add(DataFactory::TYPE_ARTICLE);
                    $array->add(DataFactory::TYPE_STRING);
                    $array->add(DataFactory::TYPE_LIST_STRING);
                    $array->add(DataFactory::TYPE_LIST_ARTICLE);
                }
                else{
                    //out same as in

                    $array->add(DataFactory::SAME);
                }
                break;
            case self::TASK_SENDER_EMAIL:
                //TaskSenderEmail
                if($in){
                    //in
                    $array->add(DataFactory::SAME);
                }
                else{
                    //out
                    $array->add(DataFactory::SAME);
                }
                break;
            case self::TASK_SENDER_BLOG:
                //TaskSenderBlog
                if($in){
                    //in
                    $array->add(DataFactory::SAME);
                }
                else{
                    //out
                    $array->add(DataFactory::SAME);
                }
                break;
            case self::TASK_SENDER_SOCIAL:
                //TaskSenderSocial
                if($in){
                    //in
                    $array->add(DataFactory::SAME);
                }
                else{
                    //out
                    $array->add(DataFactory::SAME);
                }
                break;
            case self::TASK_ACTION_ARRANGER_TEXT:
                //TaskActionArrangerText
                if($in){
                    //in
                    $array->add(DataFactory::SAME);
                }
                else{
                    //out
                    $array->add(DataFactory::TYPE_LIST_STRING);
                }
                break;
        }

        return $array;
    }


}