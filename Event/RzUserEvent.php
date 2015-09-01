<?php



namespace Rz\UserBundle\Event;


use Symfony\Component\EventDispatcher\Event;


class RzUserEvent extends Event
{
    protected $user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}