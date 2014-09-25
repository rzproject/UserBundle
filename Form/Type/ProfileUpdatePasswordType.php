<?php

/*
 * This file is part of the RzUserBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileUpdatePasswordType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', 'repeated', array(
                      'attr'=>array('class'=>'span12'),
                      'type' => 'password',
                      'options' => array('translation_domain' => 'RzUserBundle', 'attr'=>array('class'=>'span12')),
                      'first_options' => array('label' => 'form.password_new'),
                      'second_options' => array('label' => 'form.password_confirmation'),
                      'invalid_message' => 'fos_user.password.mismatch',
                      ))
                ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'RzUserBundle', 'attr'=>array("readonly"=>"readonly", 'class'=>'span12')))
                ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'RzUserBundle', 'attr'=>array("readonly"=>"readonly", 'class'=>'span12')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rz_user_update_password';
    }
}
