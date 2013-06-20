<?php

namespace Kraken\AdminBundle\Controller;

use Inflexible\Inflexible;
use Kraken\AdminBundle\Form\Type\ScenarioType;
use Kraken\Entities\Data\DataArticle;
use Kraken\Entities\Scenario;
use Kraken\Factories\DataFactory;
use Kraken\Factories\TaskFactory;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $general_array[DataFactory::NONE] = $this->container->get('translator')->trans('DataNone');
        $general_array[DataFactory::SAME] = $this->container->get('translator')->trans('DataSame');
        $general_array[DataFactory::TYPE_ARTICLE] = $this->container->get('translator')->trans('DataArticle');
        $general_array[DataFactory::TYPE_DATE] = $this->container->get('translator')->trans('DataDate');
        $general_array[DataFactory::TYPE_INTEGER] = $this->container->get('translator')->trans('DataInteger');
        $general_array[DataFactory::TYPE_LIST] = $this->container->get('translator')->trans('DataList');
        $general_array[DataFactory::TYPE_STRING] = $this->container->get('translator')->trans('DataString');
        $general_array[DataFactory::TYPE_LIST_STRING] = $this->container->get('translator')->trans('DataListString');
        $general_array[DataFactory::TYPE_LIST_ARTICLE] = $this->container->get('translator')->trans('DataListArticle');

        //print_r($task_datas);exit;
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

        $form = $this->container->get('form.factory')->create(TaskFactory::getInstance()->getTypeFormInstance($type), $sc);

        $request = $this->container->get('request');

        $array_in = TaskFactory::getInstance()->defineDataByTask($type,true);
        $array_out = TaskFactory::getInstance()->defineDataByTask($type,false);
        $general_array[DataFactory::NONE] = $this->container->get('translator')->trans('DataNone');
        $general_array[DataFactory::SAME] = $this->container->get('translator')->trans('DataSame');
        $general_array[DataFactory::TYPE_ARTICLE] = $this->container->get('translator')->trans('DataArticle');
        $general_array[DataFactory::TYPE_DATE] = $this->container->get('translator')->trans('DataDate');
        $general_array[DataFactory::TYPE_INTEGER] = $this->container->get('translator')->trans('DataInteger');
        $general_array[DataFactory::TYPE_LIST] = $this->container->get('translator')->trans('DataList');
        $general_array[DataFactory::TYPE_STRING] = $this->container->get('translator')->trans('DataString');
        $general_array[DataFactory::TYPE_LIST_STRING] = $this->container->get('translator')->trans('DataListString');
        $general_array[DataFactory::TYPE_LIST_ARTICLE] = $this->container->get('translator')->trans('DataListArticle');

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
                "general_array"=>$general_array
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
            , $entity);
        $request = $this->container->get('request');

        $array_in = TaskFactory::getInstance()->defineDataByTask($request->get("type"),true);
        $array_out = TaskFactory::getInstance()->defineDataByTask($request->get("type"),false);
        $general_array[DataFactory::NONE] = $this->container->get('translator')->trans('DataNone');
        $general_array[DataFactory::SAME] = $this->container->get('translator')->trans('DataSame');
        $general_array[DataFactory::TYPE_ARTICLE] = $this->container->get('translator')->trans('DataArticle');
        $general_array[DataFactory::TYPE_DATE] = $this->container->get('translator')->trans('DataDate');
        $general_array[DataFactory::TYPE_INTEGER] = $this->container->get('translator')->trans('DataInteger');
        $general_array[DataFactory::TYPE_LIST] = $this->container->get('translator')->trans('DataList');
        $general_array[DataFactory::TYPE_STRING] = $this->container->get('translator')->trans('DataString');
        $general_array[DataFactory::TYPE_LIST_STRING] = $this->container->get('translator')->trans('DataListString');
        $general_array[DataFactory::TYPE_LIST_ARTICLE] = $this->container->get('translator')->trans('DataListArticle');


        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {

                //check if in or out is filled
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

        $in_value = $entity->getInputData()!=null?$this->container->get('translator')->trans(Inflexible::denamespace(get_class($entity->getInputData()))):0;
        $out_value = $entity->getOutputData()!=null?$this->container->get('translator')->trans(Inflexible::denamespace(get_class($entity->getOutputData()))):0;

        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:edit.html.twig',
            array(
                'form' => $form->createView(),
                'entity'=>$entity,
                "type"=>$request->get("type"),
                'value_in' =>$in_value,
                'value_out' =>$out_value,
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
                if($index>0){
                    $prev_entity = $entity->getScenario()->getTasks()->get($index-1);
                    $prev_entity->setPosition($index);
                    $entity->setPosition($index-1);

                    $this->container->get('kraken.task')->update($prev_entity);
                }

            }
            else{
                //add 1 to index
                if($index<$entity->getScenario()->getTasks()->count()-1)
                {
                    $next_entity = $entity->getScenario()->getTasks()->get($index+1);
                    $next_entity->setPosition($index);
                    $entity->setPosition($index+1);
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



}
