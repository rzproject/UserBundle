<?php

namespace Rz\userBundle\EventListener;

use Rz\UserBundle\RzUserEvents;
use Rz\UserBundle\Event\UserLoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserLoginEventListener implements EventSubscriberInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            RzUserEvents::RZ_LOGIN_PROCESS => 'processLogin',
        );
    }

    public function processLogin(UserLoginEvent $event){}
}
