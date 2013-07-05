<?php

namespace Kraken\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DashboardController
 * @package Kraken\AdminBundle\Controller
 * @author Eric Pidoux
 * @version 1.0
 */
class DashboardController extends Controller
{
    /**
     * Access to the admin dashboard
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        //Load data needed to display dashboard
        $activeScenario = $this->container->get('kraken.scenario')->countAllActiveScenario();
        $models = $this->container->get('kraken.scenario')->countAllActiveModel();
        $activeUsers = $this->container->get('kraken.user')->countAllActive();

        return $this->render('KrakenAdminBundle:Dashboard:index.html.twig',
            array(
                "active_scenario"=>$activeScenario,
                "models"=>$models,
                "active_users"=>$activeUsers
            )
        );
    }
}
