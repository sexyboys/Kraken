<?php

namespace Kraken\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Epidoux\HybridAuthBundle\Factories\ServiceFactory;
use Inflexible\Inflexible;
use Kraken\AdminBundle\Form\Type\ScenarioType;
use Kraken\UserBundle\Entity\DataArticle;
use Kraken\UserBundle\Entity\Scenario;
use Kraken\UserBundle\Entity\Tag;
use Kraken\UserBundle\Entity\TaskActionArrangerText;
use Kraken\Factories\DataFactory;
use Kraken\Factories\TaskFactory;
use Kraken\Managers\Services\BlogService;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Kraken\Managers\Services\DisplayLogService;
use Kraken\Managers\Services\SocialService;

/**
 * Class TaskController
 * @package Kraken\AdminBundle\Controller
 * @author Eric Pidoux
 * @version 1.0
 */
class TaskController extends ContainerAware
{
    /**
     * Access to the admin list of task for a scenario model
     * @param id of the scenario
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($id)
    {
        //List all tasks of a scenario
        $scenario = $this->container->get('kraken.scenario')->find($id);

        //Generate array of Task type =>Datas
        $task_datas = TaskFactory::getInstance()->getTaskTypesWithDatas();

        //Prepare kind of datas in and out for all kind of tasks
        $general_array = DataFactory::getInstance()->getDatanamesArray();

        //print_r($task_datas);exit;
        //print_r($scenario->getTasks());exit;
        $this->container->get('session')->set('page',$this->container->get('translator')->trans('admin.page.task'));
        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:list.html.twig',
            array(
                "scenario"=>$scenario,
                "general_array"=>$general_array,
                'task_datas'=>$task_datas
            )
        );
    }

    /**
     * Prepare add of task
     * @param $id the scenario id
     * @return listview
     */
    public function requestAddAction($id)
    {

        $request = $this->container->get('request');
        //load the list of Tasks type
        $types = TaskFactory::getInstance()->loadTypes();
        if ('POST' == $request->getMethod()) {

            $type = $request->get('type');
            //get the type
            return $this->addAction($id,$type,true);

        }


        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:request_add.html.twig',
            array(
                'types' => $types,
                'id_scenario'=>$id
            )
        );
    }

    /**
     * Add a task item
     * @param id of the scenario
     * @param index of type of task
     * @param first the first time called
     * @return listview
     */
    public function addAction($id,$type,$first=false)
    {
        $sc = TaskFactory::getInstance()->getTypeInstance($type);

        $params = null;
        if($type == TaskFactory::TASK_ACTION_ARRANGER_TEXT && $sc->getXslt()=="")
        {
            $str = '<?xml version="1.0" encoding="ISO-8859-1"?>
                    <xsl:stylesheet
                        version="1.0"
                        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                        xmlns="http://www.w3.org/TR/REC-html40" result-ns="">
                    <xsl:template match="datas">
                       <html> <head>
<meta http-equiv="Content-Type"
    content="text/html; charset=ISO-8859-1" /></head><body>
                               <xsl:apply-templates/>
                       </body> </html>
                    </xsl:template>
                    <xsl:template match="data">
                       <div>
                               <xsl:apply-templates/>
                       </div>
                    </xsl:template>
                    <xsl:template match="title">
                       <h3>
                               <xsl:apply-templates/>
                       </h3>
                    </xsl:template>
                    <xsl:template match="date">
                       <i>
                               <xsl:apply-templates/>
                       </i>
                    </xsl:template>
                    <xsl:template match="content">
                       <p>
                               <xsl:apply-templates/>
                       </p>
                    </xsl:template>

                    </xsl:stylesheet>';
            $sc->setXslt($str);
        }
        else if($type == TaskFactory::TASK_SENDER_BLOG)
        {
            $params = array(
                        BlogService::TYPE_WORDPRESS => $this->container->get('translator')->trans(BlogService::TYPE_WORDPRESS),
                        BlogService::TYPE_GOOGLE => $this->container->get('translator')->trans(BlogService::TYPE_GOOGLE),
                        BlogService::TYPE_TRUMBLR => $this->container->get('translator')->trans(BlogService::TYPE_TUMBLR),
                        BlogService::TYPE_OTHER => $this->container->get('translator')->trans(BlogService::TYPE_OTHER)
                    );
        }
        else if($type == TaskFactory::TASK_SENDER_SOCIAL)
        {
            $params = array(
                SocialService::TYPE_TWITTER=> $this->container->get('translator')->trans(SocialService::TYPE_TWITTER),
                SocialService::TYPE_FACEBOOK => $this->container->get('translator')->trans(SocialService::TYPE_FACEBOOK),
                SocialService::TYPE_GOOGLE => $this->container->get('translator')->trans(SocialService::TYPE_GOOGLE)
            );
        }

        $form = $this->container->get('form.factory')->create(TaskFactory::getInstance()->getTypeFormInstance($type,$params), $sc);

        $request = $this->container->get('request');

        $array_in = TaskFactory::getInstance()->defineDataByTask($type,true);
        $array_out = TaskFactory::getInstance()->defineDataByTask($type,false);
        $general_array = DataFactory::getInstance()->getDatanamesArray();

        if ('POST' == $request->getMethod() && !$first) {
            $form->bind($request);
            if ($form->isValid()) {

                //check if in or out is filled
                if($request->get('out')!=null)
                {
                    $index = $request->get('out');
                    $value = $general_array[$index];
                    $sc->setChosenOutputData($value);

                }
                if($request->get('in')!=null)
                {
                    $index = $request->get('in');
                    $sc->setChosenInputData($general_array[$index]);
                }

                $scenario = $this->container->get('kraken.scenario')->find($id);
                $sc->setScenario($scenario);
                if(!$scenario->getTasks()->isEmpty())
                {
                    $t = $scenario->getTasks()->last();
                    $sc->setPosition($t->getPosition()+1);
                }
                else $sc->setPosition(1);

                //save entity
                $this->container->get('kraken.task')->update($sc);

                //reload data
                //$this->container->get('kraken.task')->reloadDatasByTask($scenario,$sc);

                //Flash msg
                $this->container->get('session')->getFlashBag()->add('success',
                    $this->container->get('translator')->trans('admin.msg.success.task.add')
                );


                return new RedirectResponse(
                    $this->container->get('router')->generate("kraken_admin_scenario_tasks",array("id"=>$id)));


            }
            else{

                $this->container->get('session')->getFlashBag()->add('error',
                    $this->container->get('translator')->trans('admin.msg.error.task.add')
                );
            }
        }


        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:new.html.twig',
            array('form' => $form->createView(),
                'type' => $type,
                'task_type' =>$this->container->get('translator')->trans(Inflexible::denamespace(get_class($sc))),
                'id_scenario'=>$id,
                'array_in'=>$array_in,
                'array_out'=>$array_out,
                "general_array"=>$general_array,
                "params"=>$params,
                "type_task_social"=>TaskFactory::TASK_SENDER_SOCIAL
            )
        );
    }

    /**
     * edit an item
     * @param Request $request
     * @return list
     */
    public function editAction(Request $request)
    {
        //retrieve entity by id
        $entity = $this->container->get('kraken.task')->find($request->get('id'));
        $index = TaskFactory::getInstance()->getTypeIndex($request->get("type"));
        $form = $this->container->get('form.factory')->create(
            TaskFactory::getInstance()->getTypeFormInstance($index)
            , $entity
        );
        $request = $this->container->get('request');

        $array_in = TaskFactory::getInstance()->defineDataByTask($request->get("type"),true);
        $array_out = TaskFactory::getInstance()->defineDataByTask($request->get("type"),false);
        $general_array = DataFactory::getInstance()->getDatanamesArray();

        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                //in or out is filled
                if($request->get('out')!=null)
                {
                    $index = $request->get('out');
                    $value = $general_array[$index];
                    $entity->setChosenOutputData($value);

                }
                if($request->get('in')!=null)
                {
                    $index = $request->get('in');
                    $entity->setChosenInputData($general_array[$index]);
                }

                $this->container->get('kraken.task')->update($entity);

                //Reload task
                $this->container->get('kraken.task')->reloadTask($entity);

                //save it
                $this->container->get('kraken.task')->update($entity);

                //Flash msg
                $this->container->get('session')->getFlashBag()->add('success',
                    $this->container->get('translator')->trans('admin.msg.success.task.edit')
                );

                return new RedirectResponse(
                    $this->container->get('router')->generate("kraken_admin_scenario_tasks",array("id"=>$entity->getScenario()->getId())));
            }
            else{

                $this->container->get('session')->getFlashBag()->add('error',
                    $this->container->get('translator')->trans('admin.msg.error.task.edit')
                );
            }
        }
        //generate tags for form
        /*$tags = $entity->getTags();
        $array = array();
        foreach($tags as $tag)
        {
            $array[]['name']=$tag->getName();
            $array[]['regex']=$tag->getRegex();
            $array[]['type']=$tag->getType();
        }
        $entity->setTags($array);*/

        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:edit.html.twig',
            array(
                'form' => $form->createView(),
                'entity'=>$entity,
                "type"=>$request->get("type"),
                'array_in'=>$array_in,
                'array_out'=>$array_out,
                "general_array"=>$general_array
            )
        );

    }

    /**
     * Delete an item
     * @param $id
     * @return list
     */
    public function deleteAction($id)
    {
        try{
            $entity = $this->container->get('kraken.task')->find($id);
            $scenario_id = $entity->getScenario()->getId();
            $this->container->get('kraken.task')->remove($entity);
            $this->container->get('session')->getFlashBag()->add('success',
                $this->container->get('translator')->trans('admin.msg.success.task.delete')
            );
            return new RedirectResponse(
                $this->container->get('router')->generate("kraken_admin_scenario_tasks",array("id"=>$scenario_id)));
        }
        catch(\Exception $e){
            $this->container->get('session')->getFlashBag()->add('error',
                $this->container->get('translator')->trans('admin.msg.error.task.delete')
            );
            $this->container->get('logger')->err('Error while deleting a task :'.$e->getCode().':'.$e->getMessage());
            return new RedirectResponse(
                $this->container->get('router')->generate("kraken_admin_scenario_tasks",array("id"=>$scenario_id)));
        }
    }

    /**
     * move an item
     * @param $id the task id
     * @param $position 0=substrate a rank , 1=add a rank
     * @return list
     */
    public function moveAction($id,$position)
    {
        try{
            $entity = $this->container->get('kraken.task')->find($id);
            $scenario_id = $entity->getScenario()->getId();
            //find the index load the previous and change with him
            $index = $entity->getScenario()->getTasks()->indexOf($entity);
            if($position==0){
                if($index>0){//if it's not the first, get the previous and switch position on them
                    $prev_entity = $entity->getScenario()->getTasks()->get($index-1);
                    $prev_entity->setPosition($entity->getPosition());
                    $entity->setPosition($entity->getPosition()-1);

                    $this->container->get('kraken.task')->update($prev_entity);
                }

            }
            else{
                //add 1 to index
                if($index<$entity->getScenario()->getTasks()->count()-1)
                {
                    $next_entity = $entity->getScenario()->getTasks()->get($index+1);
                    $next_entity->setPosition($entity->getPosition());
                    $entity->setPosition($entity->getPosition()+1);
                    $this->container->get('kraken.task')->update($next_entity);
                }
            }
            $this->container->get('kraken.task')->update($entity);
            $this->container->get('session')->getFlashBag()->add('success',
                $this->container->get('translator')->trans('admin.msg.success.task.move')
            );
            return new RedirectResponse(
                $this->container->get('router')->generate("kraken_admin_scenario_tasks",array("id"=>$scenario_id)));
        }
        catch(\Exception $e){
            $this->container->get('session')->getFlashBag()->add('error',
                $this->container->get('translator')->trans('admin.msg.error.task.move')
            );
            $this->container->get('logger')->err('Error while moving a task :'.$e->getCode().':'.$e->getMessage());
            return new RedirectResponse(
                $this->container->get('router')->generate("kraken_admin_scenario_tasks",array("id"=>$scenario_id)));
        }
    }



    /**
     * Define input item
     * @param $id
     * @return list
     */
    public function inputAction($id)
    {

        //retrieve entity by id
        $entity = $this->container->get('kraken.task')->find($id);
        $index = $this->container->get('kraken.task')->getTypeIndex($request->get("type"));
        $form = $this->container->get('form.factory')->create(
            $this->container->get('kraken.task')->getTypeFormInstance($index)
            , $entity);
        $request = $this->container->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {

                //save it
                $this->container->get('kraken.task')->update($entity);

                //Flash msg
                $this->container->get('session')->getFlashBag()->add('success',
                    $this->container->get('translator')->trans('admin.msg.success.task.edit')
                );

                return new RedirectResponse(
                    $this->container->get('router')->generate("kraken_admin_scenario_tasks",array("id"=>$entity->getScenario()->getId())));
            }
            else{

                $this->container->get('session')->getFlashBag()->add('error',
                    $this->container->get('translator')->trans('admin.msg.error.task.edit')
                );
            }
        }

        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:edit.html.twig',
            array(
                'form' => $form->createView(),
                'entity'=>$entity,
                "type"=>$request->get("type")
            )
        );
    }



    /**
     * Execute task (previous if exists until the given one and display result)
     * @param $id Task id
     * @return display result
     */
    public function executeAction($id)
    {
        $task = $this->container->get('kraken.task')->find($id);

        try{
            $this->container->get('kraken.displaylog')->clear();
            $result = $this->container->get('kraken.scenario')->executeToDisplay($task->getScenario(),$task);
        }
        catch(\Exception $exception)
        {
            $this->container->get('logger')->err("Error while executing task ".$id." to display : ".$exception->getMessage());

            $this->container->get('kraken.displaylog')->display('execute.display.task.error',array("%msg%"=>$exception->getCode()),DisplayLogService::TYPE_ERROR, $exception);

        }
        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:execute.html.twig',
            array(
                'result'=>$this->container->get('kraken.displaylog')->getLog(),
                "scenario"=>$task->getScenario(),
                "task"=>$task
            )
        );
    }


    /**
     * Link user to social account
     */
    public function socialAction(Request $request)
    {
        $task = $this->container->get("kraken.task")->find($request->get('id'));
        $empty = true;
        $request = $this->container->get('request');
        if($task->getLogin()!="")$empty = false;
        $user_info = $this->container->get('hybridauth.auth')->getUserInfo();
        if($user_info!=null)
        {
            print_r($user_info);exit;
        }
        $provider=null;
        if($task->getSocialType() == SocialService::TYPE_TWITTER) $provider= ServiceFactory::TWITTER;


        $template = "KrakenAdminBundle:Task:link_social.html.twig";

        return $this->container->get('templating')->renderResponse(
            $template,
            array(
                'type' => $task->getSocialType(),
                'empty' => $empty,
                "id" => $task->getId(),
                "scenario_id"=>$task->getScenario()->getId(),
                "provider"=>$provider
            )
        );
    }

}
