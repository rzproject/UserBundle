<?php



namespace Rz\UserBundle\Event\Listener;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Rz\UserBundle\Model\PasswordExpireConfigManagerInterface;

class InteractiveLoginListener
{
    protected $userManager;
    protected $configManager;
    protected $session;

    public function __construct(Session $session, UserManagerInterface $userManager, PasswordExpireConfigManagerInterface $configManager)
    {
        $this->session = $session;
        $this->userManager = $userManager;
        $this->configManager = $configManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof UserInterface) {

            if($user->getExpiresAt()) {
                $daysToExpire = $user->getExpiresAt()->diff(new \DateTime());
                if ($daysToExpire->format('%a') >=  0 ) {
                    $this->session->set('_rz_user.account_expired.'.$user->getId(), 'account_expired');
                }
            }

            if($user->getCredentialsExpireAt()) {
                $daysToExpire = $user->getCredentialsExpireAt()->diff(new \DateTime());
                if ($daysToExpire->format('%a') >=  0 ) {
                    $this->session->set('_rz_user.credentials_expired.'.$user->getId(), 'credentials_expired');
                }
            }

            //password expire
            if($expiryDays = $this->configManager->getDaysToExpire() && $route = $this->configManager->getRedirectRoute() && $user->getPasswordChangedAt()) {
                $daysLastChange = $user->getPasswordChangedAt()->diff(new \DateTime());
                if ($daysLastChange->format('%a') >  (int) $expiryDays ) {
                    $this->session->set('_rz_user.password_expire.'.$user->getId(), 'password_expire');
                }
            }
        }
    }
}
