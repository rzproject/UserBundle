<?php



namespace Rz\UserBundle\Model;

use Rz\SearchBundle\Exception\ConfigManagerException;
use Doctrine\ORM\PersistentCollection;

class PasswordStrengthConfigManager implements PasswordStrengthConfigManagerInterface
{
    /** @var array */
    protected $configs;

    /**
     * Creates a CKEditor config manager.
     *
     * @param array $configs The CKEditor configs.
     */
    public function __construct(array $configs = array())
    {
        $this->setConfigs($configs);
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

    /**
     * {@inheritdoc}
     */
    public function getRequirementMinLength()
    {
        return isset($this->configs['requirement']['min_length']) ? $this->configs['requirement']['min_length'] : 8;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirementRequireLetters()
    {
        return isset($this->configs['requirement']['require_letters']) ? $this->configs['requirement']['require_letters'] : true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirementRequireCaseDiff()
    {
        return isset($this->configs['requirement']['require_case_diff']) ? $this->configs['requirement']['require_case_diff'] : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirementRequireNumbers()
    {
        return isset($this->configs['requirement']['require_numbers']) ? $this->configs['requirement']['require_numbers'] : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirementRequireSpecialCharacter()
    {
        return isset($this->configs['requirement']['require_special_character']) ? $this->configs['requirement']['require_special_character'] : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getStrengthMinLength()
    {
        return isset($this->configs['strength']['min_length']) ? $this->configs['strength']['min_length'] : 8;
    }

    /**
     * {@inheritdoc}
     */
    public function getStrengthMinStrength()
    {
        return isset($this->configs['strength']['min_strength']) ? $this->configs['strength']['min_strength'] : false;
    }
}
