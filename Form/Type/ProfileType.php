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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileType extends AbstractType
{
    protected $class;

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
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        $this->buildUserForm($builder, $options);

        $builder->add('current_password', 'password', array(
                                            'label' => 'form.current_password',
                                            'translation_domain' => 'SonataUserBundle',
                                            'mapped' => false,
                                            'constraints' => $constraint,
                                        ));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'profile',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rz_user_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }


    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $now = new \DateTime();

        $builder
            ->add('username', null, array('label' => 'form.label_username', 'translation_domain' => 'SonataUserBundle'))
            ->add('email', 'email', array('label' => 'form.label_email', 'translation_domain' => 'SonataUserBundle', 'read_only'=>true, 'attr'=>array('class'=>'span12')))
            ->add('firstname', null, array('label' => 'form.label_firstname', 'translation_domain' => 'SonataUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('lastname', null, array('label' => 'form.label_lastname','translation_domain' => 'SonataUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('website', null, array('label' => 'form.label_website', 'translation_domain' => 'SonataUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('phone', null, array('required' => false, 'label' => 'form.label_phone', 'translation_domain' => 'SonataUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('dateOfBirth', 'sonata_type_date_picker', array(
                'years'       => range(1900, $now->format('Y')),
                'dp_min_date' => '1-1-1900',
                'dp_max_date' => $now->format('c'),
                'required'    => false,
            ))
            ->add('gender', 'choice', array(
                              'label' => 'form.label_gender',
                              'choices' => array(
                                  UserInterface::GENDER_UNKNOWN => 'gender_unknown',
                                  UserInterface::GENDER_FEMALE  => 'gender_female',
                                  UserInterface::GENDER_MAN     => 'gender_male',
                              ),
                              'required' => true,
                              'translation_domain' => 'SonataUserBundle',
                              'attr'=>array('class'=>'span12')
                          ));
    }
}
