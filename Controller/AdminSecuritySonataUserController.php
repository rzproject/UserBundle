<?php



namespace Rz\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Controller\AdminSecurityController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class AdminSecuritySonataUserController extends AdminSecurityController
{
    /**
     * {@inheritdoc}
     */
    public function loginAction(Request $request = null)
    {
        $request = $request === null ? $request = $this->get('request') : $request;

        $user = $this->getUser();

        if ($user instanceof UserInterface) {
            $this->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->generateUrl('sonata_admin_dashboard');

            return $this->redirect($url);
        }

        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        if ($this->has('security.csrf.token_manager')) { // sf >= 2.4
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate');
        } else {
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $refererUri = $request->server->get('HTTP_REFERER');

            return $this->redirect($refererUri && $refererUri != $request->getUri() ? $refererUri : $this->generateUrl('sonata_admin_dashboard'));
        }

        // TODO: Deprecated in 2.3, to be removed in 3.0
        try {
            $resetRoute = $this->generateUrl('sonata_user_admin_resetting_request');
        } catch (RouteNotFoundException $e) {
            @trigger_error('Using the route fos_user_resetting_request for admin password resetting is deprecated since version 2.3 and will be removed in 3.0. Use sonata_user_admin_resetting_request instead.', E_USER_DEPRECATED);
            $resetRoute = $this->generateUrl('fos_user_resetting_request');
        }

        return $this->render('RzUserBundle:Admin:Security/login.html.'.$this->container->getParameter('fos_user.template.engine'), array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,
            'base_template' => $this->get('sonata.admin.pool')->getTemplate('layout'),
            'admin_pool'    => $this->get('sonata.admin.pool'),
            'reset_route'   => $resetRoute, // TODO: Deprecated in 2.3, to be removed in 3.0
        ));
    }
}
