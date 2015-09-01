<?php

namespace Rz\UserBundle\Model;


/**
 * Represents a User model
 */
abstract class UserAuthenticationLogs implements UserAuthenticationLogsInterface
{
    protected $user;
    protected $logDate;
    protected $type;

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

    /**
     * @return mixed
     */
    public function getLogDate()
    {
        return $this->logDate;
    }

    /**
     * @param mixed $logDate
     */
    public function setLogDate($logDate)
    {
        $this->logDate = $logDate;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}