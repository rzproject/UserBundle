<?php

namespace Rz\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('new', 'repeated', array(
                        'type' => 'password',
                        'options' => array('translation_domain' => 'SonataUserBundle'),
                        'first_options' => array(
                            'label' => 'form.password',
                            'attr' => array('placeholder'=>'form.password'),
                            'required'    => true,
                        ),
                        'second_options' => array(
                            'label' => 'form.password_confirmation',
                            'attr' => array('placeholder'=>'form.password_confirmation'),
                            'required'    => true,
                        ),
                        'invalid_message' => 'fos_user.password.mismatch',)
            );
    }

    /**
     * {@inheritdoc}
     *
     * @todo Remove it when bumping requirements to SF 2.7+
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rz\UserBundle\Form\Model\ChangePassword',
            'intention'  => 'resetting',
            'validation_groups' => array('Registration'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rz_user_resetting';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
