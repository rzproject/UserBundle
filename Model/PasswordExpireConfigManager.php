<?php



namespace Rz\UserBundle\Model;

use Rz\SearchBundle\Exception\ConfigManagerException;
use Doctrine\ORM\PersistentCollection;

class PasswordExpireConfigManager extends AbstractConfigManager
{
    /**
     * {@inheritdoc}
     */
    public function getDaysToExpire()
    {
        return isset($this->configs['password_expire']['days_to_expire']) ? $this->configs['password_expire']['days_to_expire'] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectRoute()
    {
        return isset($this->configs['password_expire']['redirect_route']) ? $this->configs['password_expire']['redirect_route'] : null;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
}
