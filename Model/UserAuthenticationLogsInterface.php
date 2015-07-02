<?php

namespace Rz\UserBundle\Model;

interface UserAuthenticationLogsInterface {

    /**
     * @return mixed
     */
    public function getUser();

    /**
     * @param mixed $user
     */
    public function setUser($user);

    /**
     * @return mixed
     */
    public function getLogDate();

    /**
     * @param mixed $logDate
     */
    public function setLogDate($logDate);

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @param mixed $type
     */
    public function setType($type);
}