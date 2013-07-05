<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kraken\UserBundle\Controller;

use Kraken\Entities\User;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

/**
 * Controller managing the registration
 * Overriding the FOSUSER bundle
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        if($this->container->getParameter('module_register')==0)
        {
            //redirect on index
            return new RedirectResponse($this->container->get('router')->generate('kraken_user_homepage'));
        }
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            /*****************************************************
             * Add new functionality (e.g. log the registration) *
             *****************************************************/
            $this->container->get('logger')->info(
                sprintf('New user registration: %s', $user)
            );

            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $this->authenticateUser($user);
                $route = 'fos_user_registration_confirmed';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);

            return new RedirectResponse($url);
        }

        return $this->container->get('templating')->renderResponse('KrakenUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        //print_r($user);exit;
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $this->container->get('session')->set('page',$this->container->get('translator')->trans('app.page.register'));

       $template = 'KrakenUserBundle:Registration:confirmed.html.twig';

        return $this->container->get('templating')->renderResponse($template, array(
            'user' => $user,
        ));
    }

}
