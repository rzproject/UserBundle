<?php

namespace Rz\UserBundle\Provider;


use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\ClassificationBundle\Model\CollectionInterface;
use Rz\ClassificationBundle\Provider\Collection\BaseProvider;

class AgeDemographicsCollectionProvider extends BaseProvider
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
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
