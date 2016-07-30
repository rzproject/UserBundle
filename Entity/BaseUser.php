<?php

namespace Rz\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as AbstractedUser;

class BaseUser extends AbstractedUser
{
    protected $passwordChangedAt;

    /**
     * @return mixed
     */
    public function getPasswordChangedAt()
    {
        return $this->passwordChangedAt;
    }

    /**
     * @param mixed $passwordChangedAt
     */
    public function setPasswordChangedAt($passwordChangedAt)
    {
        $this->passwordChangedAt = $passwordChangedAt;
    }

    public function setPlainPassword($password)
    {
        $this->setPasswordChangedAt(new \DateTime());
        return parent::setPlainPassword($password);
    }
}
