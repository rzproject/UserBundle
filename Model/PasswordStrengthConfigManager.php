<?php



namespace Rz\UserBundle\Model;

use Rz\SearchBundle\Exception\ConfigManagerException;
use Doctrine\ORM\PersistentCollection;

class PasswordStrengthConfigManager extends AbstractConfigManager
{
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
