<?php

namespace Rz\UserBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Rz\UserBundle\Model\PasswordStrengthConfigManagerInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Password strength Validation.
 *
 * Validates if the password strength is equal or higher
 * to the required minimum.
 *
 * The strength is computed from various measures including
 * length and usage of characters.
 *
 * The strengths are marked up as follow.
 *  1: Very Weak
 *  2: Weak
 *  3: Medium
 *  4: Strong
 *  5: Very Strong
 *
 * FORKED VERSION FROM:
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 * @author Shouvik Chatterjee <mailme@shouvik.net>
 */
class PasswordStrengthValidator extends ConstraintValidator
{


    protected $configManager;

    /**
     * @param PasswordStrengthConfigManagerInterface $configManager
     */
    public function __construct(PasswordStrengthConfigManagerInterface $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * @param string $password
     * @param PasswordStrength|Constraint $constraint
     */
    public function validate($password, Constraint $constraint)
    {
        if (null === $password || '' === $password) {
            return;
        }

        if (null !== $password && !is_scalar($password) && !(is_object($password) && method_exists($password, '__toString'))) {
            throw new UnexpectedTypeException($password, 'string');
        }

        $password = (string) $password;

        $passwordStrength = 0;
        $passLength = strlen($password);

        $minLength = $this->configManager->getStrengthMinLength() ?: $constraint->minLength;

        if ($passLength < $minLength) {

            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{length}}', $minLength)
                    ->addViolation();
            } else {
                $this->context->addViolation($constraint->message, array('{{length}}' => $minLength));
            }
            return;
        }

        $alpha = $digit = $specialChar = false;

        if ($passLength > $minLength) {
            $passwordStrength++;
        }

        $requireLetters = $this->configManager->getRequirementRequireLetters() ?:$constraint->requireLetters;

        if($requireLetters) {
            if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) {
                $alpha = true;
                $passwordStrength++;
            }
        } else {
            $passwordStrength++;
        }


        $requireNumbers = $this->configManager->getRequirementRequireNumbers() ?: $constraint->requireNumbers;
        if($requireNumbers) {
            if (preg_match('/\d+/', $password)) {
                $digit = true;
                $passwordStrength++;
            }
        } else {
            $passwordStrength++;
        }


        $requireSpecialCharacter =  $this->configManager->getRequirementRequireSpecialCharacter() ?: $constraint->requireSpecialCharacter;
        if($requireSpecialCharacter) {
            if (preg_match('/[^a-zA-Z0-9]/', $password)) {
                $specialChar = true;
                $passwordStrength++;
            }
        } else {
            $passwordStrength++;
        }

        if ($passLength > 12) {
            $passwordStrength++;
        }

        // No decrease strength on weak combinations

        // Only digits no alpha or special char
        if ($digit && !$alpha && !$specialChar) {
            $passwordStrength--;
        } elseif ($alpha && !$digit) {
            $passwordStrength--;
        }

        $minStrength = $this->configManager->getStrengthMinStrength() ?: $constraint->minStrength;
        if ($passwordStrength < $minStrength) {

            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{length}}', $minLength)
                    ->addViolation();
            } else {
                $this->context->addViolation($constraint->message, array('{{length}}' => $minStrength));
            }
        }
    }
}
