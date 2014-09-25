<?php

namespace Rz\UserBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Rz\UserBundle\Model\PasswordStrengthConfigManagerInterface;

class PasswordRequirementsValidator extends ConstraintValidator
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
     * @param string                          $value
     * @param PasswordRequirements|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $minLength = $this->configManager->getRequirementMinLength() ?: $constraint->minLength;

        if ($minLength > 0 && (strlen($value) < $minLength)) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->tooShortMessage)
                    ->setParameter('{{length}}', $minLength)
                    ->addViolation();
            } else {
                $this->context->addViolation($constraint->tooShortMessage, array('{{length}}' => $minLength));
            }
        }

        $requireLetters = $this->configManager->getRequirementRequireLetters() ?:$constraint->requireLetters;
        if ($requireLetters && !preg_match('/\pL/', $value)) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->missingLettersMessage)->addViolation();
            } else {
                $this->context->addViolation($constraint->missingLettersMessage, array());
            }
        }

        $requireCaseDiff = $this->configManager->getRequirementRequireCaseDiff() ?: $constraint->requireCaseDiff;
        if ($requireCaseDiff && !preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/', $value)) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->requireCaseDiffMessage)->addViolation();
            } else {
                $this->context->addViolation($constraint->requireCaseDiffMessage, array());
            }
        }

        $requireNumbers = $this->configManager->getRequirementRequireNumbers() ?: $constraint->requireNumbers;
        if ($requireNumbers && !preg_match('/\pN/', $value)) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->missingNumbersMessage)->addViolation();
            } else {
                $this->context->addViolation($constraint->missingNumbersMessage, array());
            }
        }

        $requireSpecialCharacter =  $this->configManager->getRequirementRequireSpecialCharacter() ?: $constraint->requireSpecialCharacter;
        if ($requireSpecialCharacter && !preg_match('/[^\da-zA-Z]/', $value)) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->missingSpecialCharacterMessage)->addViolation();
            } else {
                $this->context->addViolation($constraint->missingSpecialCharacterMessage, array());
            }
        }
    }
}
