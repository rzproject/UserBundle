<?php



namespace Rz\UserBundle\Controller;

use Sonata\UserBundle\Controller\SecurityFOSUser1Controller;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SecuritySonataUserController extends SecurityFOSUser1Controller
{

    public function loginAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->container->get('router')->generate('fos_user_profile_show');

            return new RedirectResponse($url);
        }

        return parent::loginAction();
    }

    protected function renderLogin(array $data)
    {
        $template = $this->container->get('rz_admin.template.loader')->getTemplates();
        return $this->container->get('templating')->renderResponse($template['rz_user.template.login'], $data);
    }
}