<?php



namespace Rz\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Sonata\UserBundle\Controller\ProfileFOSUser1Controller;

class ProfileSonataUserController extends ProfileFOSUser1Controller
{
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
            case 'SonataUserBundle:Profile:show.html.twig':
                return parent::render($template['rz_user.template.profile.show'], $parameters, $response);
                break;

            case 'SonataUserBundle:Profile:edit_authentication.html.twig':
                return parent::render($template['rz_user.template.profile.edit_authentication'], $parameters, $response);
                break;

            case 'SonataUserBundle:Profile:edit_profile.html.twig':
                return parent::render($template['rz_user.template.profile.edit'], $parameters, $response);
                break;

            default:
                return parent::render($view, $parameters, $response);
                break;
        }
    }
}
