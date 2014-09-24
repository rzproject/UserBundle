<?php



namespace Rz\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\FOSUserEvents;


class ProfileSonataUserController extends Controller
{
    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('SonataUserBundle:Profile:show.html.twig', array(
            'user'   => $user,
            'blocks' => $this->container->getParameter('sonata.user.configuration.profile_blocks')
        ));
    }

    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function editAuthenticationAction()
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('rz.user.change_password.form');

        $formHandler = $this->container->get('rz.user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('rz_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('RzUserBundle:Profile:edit_authentication.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function editProfileAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('rz.user.profile.form');
        $formHandler = $this->container->get('rz.user.profile.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('rz_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('RzUserBundle:Profile:edit_profile.html.twig', array(
            'form'               => $form->createView(),
            'breadcrumb_context' => 'user_profile',
        ));
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
