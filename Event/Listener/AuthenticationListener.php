<?php

namespace Rz\UserBundle\Event\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Rz\UserBundle\Entity\UserAuthenticationLogsManager;
use Sonata\UserBundle\Model\UserInterface;

class AuthenticationListener
{
	protected $userAuthenticationLogsManager;
    protected $logsEnabled;
	
	public function __construct(UserAuthenticationLogsManager $userAuthenticationLogsManager, $enabled = false)
	{
        $this->logsEnabled = $enabled;
		$this->userAuthenticationLogsManager = $userAuthenticationLogsManager;
	}
	
    public function onAuthenticationSuccess(InteractiveLoginEvent $event)
    {
        if($this->logsEnabled) {
            $user = $event->getAuthenticationToken()->getUser();
            if($user && $user instanceof UserInterface) {
                $log = $this->userAuthenticationLogsManager->create();
                $log->setUser($user);
                $log->setType('login');
                $this->userAuthenticationLogsManager->save($log);
            }
        }
	}
}
