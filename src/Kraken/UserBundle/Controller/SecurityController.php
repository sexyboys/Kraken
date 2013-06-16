<?php

namespace Kraken\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class SecurityController
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 * Overriding the FOSUSER bundle
 */
class SecurityController extends ContainerAware
{
	/**
	 * Sign in action rendering form login or action
	 * @param GET file the file id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 */
    public function loginAction()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();
        $error="";
        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
            $this->container->get('session')->setFlash('error',$this->container->get('translator')->trans('user.login.flash.error'));
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            $this->container->get('session')->setFlash('error',$this->container->get('translator')->trans('user.login.flash.error'));
        } else {
            $error = '';
        }

        if ($error) {
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');


        $this->container->get('session')->set('page',$this->container->get('translator')->trans('app.page.welcome'));
        
        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token' => $csrfToken
        ));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {

        $template = 'KrakenUserBundle:Index:index.html.twig';

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
