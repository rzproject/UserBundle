<?php

namespace Rz\UserBundle\Component\Authentication;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Rz\UserBundle\Entity\UserAuthenticationLogsManager;
use Sonata\UserBundle\Model\UserInterface;

class UserLogoutHandler implements LogoutHandlerInterface
{
    protected $userLogsEnabled;
    protected $userAuthenticationLogsManager;

    public function __construct(UserAuthenticationLogsManager $userAuthenticationLogsManager, $enabled = false) {

        $this->userLogsEnabled = $enabled;
        $this->userAuthenticationLogsManager = $userAuthenticationLogsManager;
    }
    public function logout(Request $request, Response $response, TokenInterface $token){
        if($this->userLogsEnabled) {
                $user = $token->getUser();
                if($user && $user instanceof UserInterface) {
                    $log = $this->userAuthenticationLogsManager->create();
                    $log->setUser($user);
                    $log->setType('logout');
                    $this->userAuthenticationLogsManager->save($log);
                }
        }
    }
}