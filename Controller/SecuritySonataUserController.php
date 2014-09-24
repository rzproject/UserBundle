<?php



namespace Rz\UserBundle\Controller;

use Sonata\UserBundle\Controller\SecurityFOSUser1Controller;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SecuritySonataUserController extends SecurityFOSUser1Controller
{

    protected function renderLogin(array $data)
    {
        $template = $this->container->get('rz_admin.template.loader')->getTemplates();
        return $this->container->get('templating')->renderResponse($template['rz_user.template.login'], $data);
    }
}