<?php

namespace Kraken\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class IndexController extends ContainerAware
{
    public function indexAction()
    {
        // last username entered by the user
        $request = $this->container->get('request');
        $session = $request->getSession();
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->container->get('templating')->renderResponse("KrakenUserBundle:Index:index.html.twig", array(
            'last_username'=>$lastUsername,
            'csrf_token'=>$csrfToken
        ));
    }
}
