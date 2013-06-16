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

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller managing the user account
 * @author Eric Pidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class AccountController extends BaseController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $user->setLastLogin(new \DateTime());
        $this->container->get('kraken.user')->update($user);
        if (!is_object($user) || !$user instanceof UserInterface) {

            $url = $this->container->get('router')->generate('kraken_user_homepage',array());
            return new RedirectResponse($url);
        }

        $template = ":Profile:show.html.twig";

        $this->container->get('session')->set('page',$this->container->get('translator')->trans('app.page.account'));

        return $this->container->get('templating')->renderResponse($template, array('user' => $user));
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            $url = $this->container->get('router')->generate('kraken_user_homepage',array());
            return new RedirectResponse($url);
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $this->container->get('session')->set('page',$this->container->get('translator')->trans('app.page.account'));

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                $userManager = $this->container->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('kraken_user_account');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }


        $template = ":Profile:edit.html.twig";

        return $this->container->get('templating')->renderResponse(
            $template,
            array('form' => $form->createView())
        );
    }

    /**
     * Remove user account
     * @return unlog redirect
     */
    public function deleteAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        try{

            $this->container->get('logger')->info('Delete user '.$user->getId());
            $this->container->get('kraken.user')->delete($user);


            $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('msg.success.user.remove'));
        }
        catch(\Exception $e)
        {
            $this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.user.remove'));
            $this->container->get('logger')->err('Error while removing user '.$user->getId().' : '.$e->getCode()." : ".$e->getMessage());
        }
        //redirect index
        $url = $this->container->get('router')->generate('fos_user_security_logout',array());
        return new RedirectResponse($url);
    }
}
