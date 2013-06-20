<?php

namespace Kraken\AdminBundle\Controller;

use Kraken\AdminBundle\Form\Type\ScenarioType;
use Kraken\Entities\Condition;
use Kraken\Managers\DataManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConditionController
 * @package Kraken\AdminBundle\Controller
 * @author Eric Pidoux
 * @version 1.0
 */
class ConditionController extends ContainerAware
{
    /**
     * Access to the admin list of condition for a task
     * @param id of the task
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($id)
    {
        //List all conditions of a task
        $task = $this->container->get('kraken.task')->find($id);

        //Special List of datas

        $array[DataManager::TYPE_ARTICLE]=$this->container->get('translator')->trans('DataArticle');
        $array[DataManager::TYPE_DATE]= $this->container->get('translator')->trans('DataDate');
        $array[DataManager::TYPE_INTEGER] = $this->container->get('translator')->trans('DataInteger');
        $array[DataManager::TYPE_STRING] = $this->container->get('translator')->trans('DataString');


        $this->container->get('session')->set('page',$this->container->get('translator')->trans('admin.page.condition'));
        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Condition:list.html.twig',
            array(
                "task"=>$task,
                "datas"=>$array
            )
        );
    }

    /**
     * Prepare add of condition
     * @param $id the task id
     * @return listview
     */
    public function requestAddAction($id)
    {

        $request = $this->container->get('request');

        //Special List of datas
        $array[DataManager::TYPE_DATE]= $this->container->get('translator')->trans('DataDate');
        $array[DataManager::TYPE_INTEGER] = $this->container->get('translator')->trans('DataInteger');
        $array[DataManager::TYPE_STRING] = $this->container->get('translator')->trans('DataString');


        if ('POST' == $request->getMethod()) {

            $type = $request->get('type');
            //get the type
            return $this->addAction($id,$type,true);

        }

        return new RedirectResponse(
            $this->container->get('router')->generate("kraken_admin_scenario_tasks_conditions",array("id"=>$id)));


    }

    /**
     * Add a condition item
     * @param task id
     * @param type the kind of data
     * @param first time execution
     * @return listview
     */
    public function addAction($id,$type,$first=false)
    {
        $task = $this->container->get('kraken.task')->find($id);

        //Prepare condition instance and ConditionType
        $condition = new Condition();
        $formType = $this->container->get('kraken.condition')->loadFormInstanceByType($type);
        $form = $this->container->get('form.factory')->create(new ConditionType(), $condition);

        $request = $this->container->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {

                //save entity
                $sc->addCondition($condition);
                $this->container->get('kraken.task')->update($sc);

                //Flash msg
                $this->container->get('session')->getFlashBag()->add('success',
                    $this->container->get('translator')->trans('admin.msg.success.condition.add')
                );


                return new RedirectResponse(
                    $this->container->get('router')->generate("kraken_admin_scenario_tasks_conditions",array("id"=>$id)));


            }
            else{

                $this->container->get('session')->getFlashBag()->add('error',
                    $this->container->get('translator')->trans('admin.msg.error.condition.add')
                );
            }
        }


        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Condition:new.html.twig',
            array('form' => $form->createView(),
                'id' => $id
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
        $entity = $this->container->get('kraken.scenario')->find($request->get('id'));

        $form = $this->container->get('form.factory')->create(new ScenarioType(), $entity);
        $request = $this->container->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {

                //save it
                $this->container->get('kraken.scenario')->update($entity);

                //Flash msg
                $this->container->get('session')->getFlashBag()->add('success',
                    $this->container->get('translator')->trans('admin.msg.success.scenario.edit')
                );

                return new RedirectResponse(
                    $this->container->get('router')->generate("kraken_admin_scenario",array()));
            }
            else{

                $this->container->get('session')->getFlashBag()->add('error',
                    $this->container->get('translator')->trans('admin.msg.error.scenario.edit')
                );
            }
        }

        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Scenario:edit.html.twig',
            array(
                'form' => $form->createView(),
                'entity'=>$entity
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
            $entity = $this->container->get('kraken.scenario')->find($id);

            $this->container->get('kraken.scenario')->remove($entity);
            $this->container->get('session')->getFlashBag()->add('success',
                $this->container->get('translator')->trans('admin.msg.success.scenario.delete')
            );
            return new RedirectResponse(
                $this->container->get('router')->generate("kraken_admin_scenario",array()));
        }
        catch(\Exception $e){
            $this->container->get('session')->getFlashBag()->add('error',
                $this->container->get('translator')->trans('admin.msg.error.scenario.delete').":".$e->getMessage()
            );
            $this->container->get('logger')->err('Error while deleting a scenario :'.$e->getCode().':'.$e->getMessage());
            return new RedirectResponse(
                $this->container->get('router')->generate("charriere_admin_history",array()));
        }
    }

    /**
     * Activate an item
     * @param  integer $id
     */
    public function activateAction($id)
    {
        try{
            $entity = $this->container->get('kraken.scenario')->find($id);
            $entity->setActive(true);
            $this->container->get('kraken.scenario')->update($entity);
            //Flash msg
            $this->container->get('session')->getFlashBag()->add('success',
                $this->container->get('translator')->trans('admin.msg.success.scenario.activate')
            );

            return new RedirectResponse(
                $this->container->get('router')->generate("kraken_admin_scenario",array()));
        }
        catch(\Exception $e){
            $this->container->get('session')->getFlashBag()->add('error',
                $this->container->get('translator')->trans('admin.msg.error.scenario.activate')
            );
        }

    }

    /**
     * Desctivate an item
     * @param  integer $id
     */
    public function desactivateAction($id)
    {
        try{
            $entity = $this->container->get('kraken.scenario')->find($id);
            $entity->setActive(false);
            $this->container->get('kraken.scenario')->update($entity);
            //Flash msg
            $this->container->get('session')->getFlashBag()->add('success',
                $this->container->get('translator')->trans('admin.msg.success.scenario.desactivate')
            );

            return new RedirectResponse(
                $this->container->get('router')->generate("kraken_admin_scenario",array()));
        }
        catch(\Exception $e){
            $this->container->get('session')->getFlashBag()->add('error',
                $this->container->get('translator')->trans('admin.msg.error.scenario.desactivate')
            );
        }

    }

}
