<?php

namespace Kraken\AdminBundle\Controller;

use Kraken\AdminBundle\Form\Type\ScenarioType;
use Kraken\Entities\Scenario;
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

        $this->container->get('session')->set('page',$this->container->get('translator')->trans('admin.page.task'));
        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:list.html.twig',
            array(
                "scenario"=>$scenario
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
        $types = $this->container->get('kraken.task')->loadTypes();
        if ('POST' == $request->getMethod()) {

        }


        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Task:request_add.html.twig',
            array('types' => $types,
                'id_scenario'=>$id
            )
        );
    }

    /**
     * Add a task item
     * @return listview
     */
    public function addAction()
    {
        $sc = new Task();

        $form = $this->container->get('form.factory')->create(new TaskType(), $sc);

        $request = $this->container->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $sc->setActive(true);

                //save entity
                $sc->setDateCreation(new \DateTime());
                $this->container->get('kraken.scenario')->update($sc);

                //Flash msg
                $this->container->get('session')->getFlashBag()->add('success',
                    $this->container->get('translator')->trans('admin.msg.success.scenario.add')
                );


                return new RedirectResponse(
                    $this->container->get('router')->generate("kraken_admin_scenario",array()));


            }
            else{

                $this->container->get('session')->getFlashBag()->add('error',
                    $this->container->get('translator')->trans('admin.msg.error.scenario.add')
                );
            }
        }


        return $this->container->get('templating')->renderResponse('KrakenAdminBundle:Scenario:new.html.twig',
            array('form' => $form->createView()
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
