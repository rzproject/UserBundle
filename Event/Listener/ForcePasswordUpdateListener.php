<?php



namespace Rz\UserBundle\Event\Listener;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Cmf\Component\Routing\ChainRouter;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Router;

use Rz\UserBundle\Model\PasswordExpireConfigManagerInterface;


class ForcePasswordUpdateListener
{
    protected $securityContext;
    protected $configManager;
    protected $session;
    protected $router;

    public function __construct(ChainRouter $router, SecurityContext $securityContext, Session $session, PasswordExpireConfigManagerInterface $configManager)
    {
        $this->securityContext = $securityContext;
        $this->configManager = $configManager;
        $this->session = $session;
        $this->router = $router;
    }

    public function onCheckPasswordExpired(GetResponseEvent $event)
    {
        if (($this->securityContext->getToken() ) && ( $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) ) {
            $user = $this->securityContext->getToken()->getUser();

            if($event->getRequest()->attributes->get('page') !== null && $event->getRequest()->attributes->get('page') instanceof \Sonata\PageBundle\Model\SnapshotPageProxy) {
                $page =$event->getRequest()->attributes->get('page')->getPage();
                if($page instanceof \Sonata\PageBundle\Model\PageInterface && $page->getName() != 'fos_user_change_password' && $this->session->get('_rz_user.password_expire.'.$user->getId()) === 'password_expire') {
                    $event->setResponse($this->getRedirectResponse());
                }
            } elseif ($this->session->get('_rz_user.password_expire.'.$user->getId()) === 'password_expire'  && $user instanceof UserInterface && $event->getRequest()->get('_route') != null && $event->getRequest()->get('_route') !== 'fos_user_change_password') {
                $event->setResponse($this->getRedirectResponse());
            }
        }
    }

    protected function getRedirectResponse() {
        $route = $this->configManager->getRedirectRoute();
        $response = new RedirectResponse($this->router->generate($route));
        $this->session->getFlashBag()->set('rz_user_error', 'password_expire.flash.error');
        return $response;
    }
}
