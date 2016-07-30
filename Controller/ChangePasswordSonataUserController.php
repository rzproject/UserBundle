<?php

namespace Rz\UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Controller\ChangePasswordFOSUser1Controller;

class ChangePasswordSonataUserController extends ChangePasswordFOSUser1Controller
{
    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function changePasswordAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $this->get('session')->remove('_rz_user.password_expire.' . $user->getId());

        $form = $this->container->get('rz.user.change_password.form');
        $formHandler = $this->container->get('rz.user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'change_password.flash.success');
            return $this->redirect($this->getRedirectionUrl($user));
        }

        $template = $this->get('rz_core.template_loader')->getTemplates();

        return $this->render($template['rz_user.template.change_password.form'], array('form' => $form->createView()));
    }
}
