<?php



namespace Rz\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser;

class User extends BaseUser
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