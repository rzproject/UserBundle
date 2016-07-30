<?php



namespace Rz\UserBundle\Model;

use Rz\SearchBundle\Exception\ConfigManagerException;
use Doctrine\ORM\PersistentCollection;

class AbstractConfigManager implements ConfigManagerInterface
{
    /** @var array */
    protected $configs;
    protected $enabled;
    
    public function __construct(array $configs = array())
    {
        $this->setConfigs($configs);
        $this->enabled = false;
    }

    /**
     * {@inheritdoc}
     */
    public function hasConfigs()
    {
        return !empty($this->configs);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfigs(array $configs)
    {
        foreach ($configs as $name => $config) {
            $this->setConfig($name, $config);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasConfig($name)
    {
        return isset($this->configs[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasConfigInConfigs($name, $config)
    {
        return isset($config[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigInConfigs($name, $config)
    {
        if($this->hasConfigInConfigs($name, $config)) {
            return $config[$name];
        } else {
            return;
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getConfig($name)
    {
        if (!$this->hasConfig($name)) {
            throw ConfigManagerException::configDoesNotExist($name);
        }

        return $this->configs[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function setConfig($name, array $config)
    {
        $this->configs[$name] = $config;
    }
}
