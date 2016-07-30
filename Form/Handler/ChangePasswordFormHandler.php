<?php



namespace Rz\UserBundle\Form\Handler;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordFormHandler
{
    protected $request;
    protected $userManager;
    protected $form;

    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->userManager = $userManager;
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->form->getData()->getPlainPassword();
    }

    public function process(UserInterface $user)
    {
        $this->form->setData($user);
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted()) {
            $isFormValid = $this->form->isValid();
            if ($isFormValid) {
                $this->onSuccess($user);
                return true;
            }
        }

        return false;
    }

    protected function onSuccess(UserInterface $user)
    {
        $user->setPlainPassword($this->getNewPassword());
        $this->userManager->updateUser($user);
    }
}
