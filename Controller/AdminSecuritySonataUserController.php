<?php



namespace Rz\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Controller\AdminSecurityController;
use Symfony\Component\Security\Core\Security;

class AdminSecuritySonataUserController extends AdminSecurityController
{
    /**
     * {@inheritdoc}
     */
    public function loginAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->container->get('router')->generate('sonata_admin_dashboard');
            return new RedirectResponse($url);
        }

        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session */

        $helper = $this->container->get('security.authentication_utils');
        $error = $helper->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $helper->getLastUsername();

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;


        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $refererUri = $request->server->get('HTTP_REFERER');

            return new RedirectResponse($refererUri && $refererUri != $request->getUri() ? $refererUri : $this->container->get('router')->generate('sonata_admin_dashboard'));
        }

        return $this->container->get('templating')->renderResponse('SonataUserBundle:Admin:Security/login.html.'.$this->container->getParameter('fos_user.template.engine'), array(
                'last_username' => $lastUsername,
                'error'         => $error,
                'csrf_token'    => $csrfToken,
                'base_template' => $this->container->get('sonata.admin.pool')->getTemplate('layout'),
                'admin_pool'    => $this->container->get('sonata.admin.pool')
            ));
    }
}
