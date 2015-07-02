<?php

namespace Rz\UserBundle\Entity;

use Rz\UserBundle\Model\UserAuthenticationLogs;

class BaseUserAuthenticationLogs extends UserAuthenticationLogs
{

    /**
     * Pre Persist method
     */
    public function prePersist()
    {
        if(!$this->logDate) {
            $this->logDate = new \DateTime();
        }
    }
}