<?php

namespace Rz\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Controller\RegistrationFOSUser1Controller;
use Rz\UserBundle\RzUserEvents;
use Rz\UserBundle\Event\RzUserEvent;

class RegistrationSonataUserController extends RegistrationFOSUser1Controller
{

    /**
     * @return RedirectResponse
     */
    public function registerAction()
    {
        $user = $this->getUser();

        if ($user instanceof UserInterface) {
            $this->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');

            return $this->redirect($this->generateUrl('sonata_user_profile_show'));
        }

        $form = $this->get('sonata.user.registration.form');
        $formHandler = $this->get('sonata.user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled) {
                $this->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $url = $this->generateUrl('sonata_user_registration_check_email');
            } else {
                $authUser = true;
                $url = $this->generateUrl('sonata_user_profile_show');
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');

            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        $this->get('session')->set('sonata_user_redirect_url', $this->get('request')->headers->get('referer'));

        return $this->render('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }




    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $template = $this->get('rz_core.template_loader')->getTemplates();
        switch ($view) {
            case 'FOSUserBundle:Registration:register.html.twig':
                return parent::render($template['rz_user.template.registration.form'], $parameters, $response);
                break;

            case 'FOSUserBundle:Registration:checkEmail.html.twig':
                return parent::render($template['rz_user.template.registration.check_email'], $parameters, $response);
                break;

            case 'FOSUserBundle:Registration:confirmed.html.twig':
                return parent::render($template['rz_user.template.registration.confirmed'], $parameters, $response);
                break;

            default:
                return parent::render($view, $parameters, $response);
                break;
        }
    }
}
