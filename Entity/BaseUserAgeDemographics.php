<?php

namespace Rz\UserBundle\Entity;

use Rz\UserBundle\Model\UserAgeDemographics;

class BaseUserAgeDemographics extends UserAgeDemographics
{

    /**
     * Pre Persist method
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Pre Update method
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

}