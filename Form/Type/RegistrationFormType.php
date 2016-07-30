<?php

namespace Rz\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotNull;


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
        $now = new \DateTime();

        $builder
            ->add('firstname', 'text', array_merge(array(
                'label' => 'form.label_firstname',
                'attr' => array('placeholder'=>'form.label_firstname'),
                'translation_domain' => 'SonataUserBundle',
            ), $this->mergeOptions))
            ->add('lastname', 'text', array_merge(array(
                'label' => 'form.label_lastname',
                'attr' => array('placeholder'=>'form.label_lastname'),
                'translation_domain' => 'SonataUserBundle',
            ), $this->mergeOptions))
            ->add('username', null, array_merge(array(
                'label' => 'form.label_username',
                'attr' => array('placeholder'=>'form.username'),
                'translation_domain' => 'SonataUserBundle',
            ), $this->mergeOptions))
            ->add('email', 'email', array_merge(array(
                'label' => 'form.label_email',
                'attr' => array('placeholder'=>'form.label_email'),
                'translation_domain' => 'SonataUserBundle',
            ), $this->mergeOptions))
            ->add('dateOfBirth', 'sonata_type_date_picker', array(
                'years'       => range(1900, $now->format('Y')),
                'dp_min_date' => '1-1-1900',
                'attr' => array('placeholder'=>'form.label_date_of_birth'),
                'translation_domain' => 'SonataUserBundle',
                'dp_max_date' => $now->format('c'),
                'required'    => false,
            ))
            ->add('gender', 'choice', array(
                'label' => 'form.label_gender',
                'data' => null,
                'choices' => array(
                    UserInterface::GENDER_UNKNOWN => 'gender_unknown',
                    UserInterface::GENDER_FEMALE  => 'gender_female',
                    UserInterface::GENDER_MAN     => 'gender_male',
                ),
                'required' => true,
                'translation_domain' => 'SonataUserBundle',
                'placeholder'=>'form.label_gender_select_one',
                'attr'=>array('class'=>'span12')
            ))

            ->add('plainPassword', 'repeated', array_merge(array(
                'type' => 'password',
                'options' => array('translation_domain' => 'SonataUserBundle'),
                'first_options' => array_merge(array(
                    'label' => 'form.password',
                    'attr' => array('placeholder'=>'form.password'),
                    'required'    => true,
                ), $this->mergeOptions),
                'second_options' => array_merge(array(
                    'label' => 'form.password_confirmation',
                    'attr' => array('placeholder'=>'form.password_confirmation'),
                    'required'    => true,
                ), $this->mergeOptions),
                'invalid_message' => 'fos_user.password.mismatch',
            ), $this->mergeOptions))

            ->add('termsAccepted', CheckboxType::class, array(
                'mapped' => false,
                'required'    => false,
                'label' => 'form.terms_accepted',
                'constraints' => array(new IsTrue(array('message'=>'message.terms_accepted'))),
                'invalid_message'=>'message.terms_accepted',
                'translation_domain' => 'SonataUserBundle',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
            'validation_groups' => array('Registration'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rz_user_register';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
