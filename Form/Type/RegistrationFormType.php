<?php

namespace Rz\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    private $class;

    /**
     * @var array
     */
    protected $mergeOptions;

    /**
     * @param string $class        The User class name
     * @param array  $mergeOptions Add options to elements
     */
    public function __construct($class, array $mergeOptions = array())
    {
        $this->class        = $class;
        $this->mergeOptions = $mergeOptions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'email', array_merge(array(
                'label' => 'form.label_firstname',
                'translation_domain' => 'RzUserBundle',
            ), $this->mergeOptions))
            ->add('lastname', 'email', array_merge(array(
                'label' => 'form.label_lastname',
                'translation_domain' => 'RzUserBundle',
            ), $this->mergeOptions))
            ->add('username', null, array_merge(array(
                'label' => 'form.label_username',
                'translation_domain' => 'RzUserBundle',
            ), $this->mergeOptions))
            ->add('email', 'email', array_merge(array(
                'label' => 'form.label_email',
                'translation_domain' => 'RzUserBundle',
            ), $this->mergeOptions))
            ->add('plainPassword', 'repeated', array_merge(array(
                'type' => 'password',
                'options' => array('translation_domain' => 'RzUserBundle'),
                'first_options' => array_merge(array(
                    'label' => 'form.label_password',
                ), $this->mergeOptions),
                'second_options' => array_merge(array(
                    'label' => 'form.label_password_confirmation',
                ), $this->mergeOptions),
                'invalid_message' => 'fos_user.password.mismatch',
            ), $this->mergeOptions))
        ;
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
            'data_class' => $this->class,
            'intention'  => 'registration',
        ));
    }

    public function getName()
    {
        return 'rz_user_registration';
    }
}
