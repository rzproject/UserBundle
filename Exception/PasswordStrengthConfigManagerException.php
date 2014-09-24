<?php

namespace Rz\UserBundle\Exception;

class PasswordStrengthConfigManagerException extends \Exception
{
    /**
     * Gets the "CONFIG DOES NOT EXIST" exception.
     *
     * @param string $name The invalid CKEditor config name.
     *
     * @return \Rz\UserBundle\Exception\PasswordStrengthConfigManagerException The "CONFIG DOES NOT EXIST" exception.
     */
    public static function configDoesNotExist($name)
    {
        return new static(sprintf('The RzUserBundle config "%s" does not exist.', $name));
    }

    /**
     * Gets the "CONFIG DOES NOT EXIST" exception.
     *
     * @param string $name The invalid CKEditor config name.
     *
     * @return \Rz\UserBundle\Exception\PasswordStrengthConfigManagerException The "CONFIG DOES NOT EXIST" exception.
     */
    public static function indexDoesNotExist($name)
    {
        return new static(sprintf('The RzUserBundle index "%s" does not exist.', $name));
    }

    /**
     * Gets the "CONFIG DOES NOT EXIST" exception.
     *
     * @param string $name The invalid CKEditor config name.
     *
     * @return \Rz\UserBundle\Exception\PasswordStrengthConfigManagerException The "CONFIG DOES NOT EXIST" exception.
     */
    public static function optionDoesNotExist($name)
    {
        return new static(sprintf('The RzUserBundle options "%s" does not exist.', $name));
    }
}
