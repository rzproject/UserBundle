<?php

namespace Rz\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordStrength extends Constraint
{
    public $message = 'rz_user.password_strength.strength.message.password_too_weak';//'password_too_weak';
    public $minLength = 6;
    public $minStrength;

    /**
     * {@inheritDoc}
     */
    public function getDefaultOption()
    {
        return 'minStrength';
    }

    public function getRequiredOptions()
    {
        return array('minStrength');
    }

    /**
     * Returns the name of the class that validates this constraint
     *
     * By default, this is the fully qualified name of the constraint class
     * suffixed with "Validator". You can override this method to change that
     * behaviour.
     *
     * @return string
     *
     * @api
     */
    public function validatedBy()
    {
        return 'rz_user.password_strength.validator.password_strength';
    }
}
