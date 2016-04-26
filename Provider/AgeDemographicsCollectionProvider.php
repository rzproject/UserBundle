<?php

namespace Rz\UserBundle\Provider;


use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\ClassificationBundle\Model\CollectionInterface;
use Rz\ClassificationBundle\Provider\BaseCollectionProvider;

class AgeDemographicsCollectionProvider extends BaseCollectionProvider
{
    protected $mediaAdmin;
    protected $mediaManager;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
    {
        $this->buildCreateForm($formMapper);
    }

    /**
     * {@inheritdoc}
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper
            ->with('Settings', array('class' => 'col-md-6'))
                ->add('settings', 'sonata_type_immutable_array', array('keys' => $this->getFormSettingsKeys($formMapper), 'attr'=>array('class'=>'rz-immutable-container')))
            ->end();
    }

    /**
     * @return array
     */
    public function getFormSettingsKeys(FormMapper $formMapper)
    {
        $settings = array(
            array('min', 'integer', array('required' => true, 'attr'=>array('class'=>'span4'))),
            array('max', 'integer',array('required' => true, 'attr'=>array('class'=>'span4'))),
        );

        return $settings;
    }

    public function load(CollectionInterface $collection) {
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(CollectionInterface $collection)
    {
        parent::prePersist($collection);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(CollectionInterface $collection)
    {
        parent::prePersist($collection);
    }


}
