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
use Sonata\UserBundle\Model\UserInterface;

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
        $builder
            ->add('firstname', null, array('label' => 'form.firstname', 'translation_domain' => 'RzUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('lastname', null, array('label' => 'form.lastname','translation_domain' => 'RzUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('website', null, array('label' => 'form.website', 'translation_domain' => 'RzUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('phone', null, array('required' => false, 'label' => 'form.phone', 'translation_domain' => 'RzUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('dateOfBirth', 'birthday', array('label' => 'form.dateOfBirth','translation_domain' => 'RzUserBundle', 'attr'=>array('class'=>'span12')))
            ->add('gender', 'choice', array(
                              'label' => 'form.gender',
                              'choices' => array(
                                  UserInterface::GENDER_UNKNOWN => 'gender_unknown',
                                  UserInterface::GENDER_FEMALE  => 'gender_female',
                                  UserInterface::GENDER_MAN     => 'gender_male',
                              ),
                              'required' => true,
                              'translation_domain' => 'RzUserBundle',
                              'attr'=>array('class'=>'span6')
                          ))
            ->add('biography', 'textarea', array('label' => 'form.biography', 'required' => false, 'translation_domain' => 'RzUserBundle', 'attr'=>array('class'=>'span12', 'rows'=>'10')))
            ->add('locale', 'locale', array('required' => false, 'attr'=>array('class'=>'span6')))
            ->add('timezone', 'timezone', array('required' => false, 'attr'=>array('class'=>'span6')));
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
        return 'rz_user_profile';
    }
}
