<?php

namespace Kraken\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('KrakenCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
