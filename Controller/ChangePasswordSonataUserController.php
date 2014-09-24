<?php

namespace Rz\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordSonataUserController extends ContainerAware
{
    public function changePasswordAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('rz.user.change_password.form');
        $formHandler = $this->container->get('rz.user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('rz_user_success', 'change_password.flash.success');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        return $this->container->get('templating')->renderResponse('RzUserBundle:ChangePassword:changePassword.html.twig', array('form' => $form->createView()));
    }

    /**
     * {@inheritdoc}
     */
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('rz_user_profile_show');
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }
}
