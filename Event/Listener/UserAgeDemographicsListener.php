<?php

namespace Rz\UserBundle\Event\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\UserBundle\Model\UserInterface;

class UserAgeDemographicsListener
{
    protected $collectionManager;
    protected $userAgeDemographicsManager;
    protected $enabled;
    protected $context;
    protected $userHelper;

    public function __construct($enabled = true, ManagerInterface $userAgeDemographicsManager, ManagerInterface $collectionManager, $context = null)
    {
        $this->enabled = $enabled;
        $this->userAgeDemographicsManager = $userAgeDemographicsManager;
        $this->collectionManager = $collectionManager;
        $this->context = $context;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($this->enabled && $entity instanceof UserInterface) {
            if ($entity->getDateOfBirth()) {
                $age = $this->getAge($entity->getDateOfBirth());
                if ($ageBracket = $this->getUserHelper()->getAgeBracket($age, $this->context)) {
                    $this->create($entity, $ageBracket);
                }
            }
        }
    }


    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($this->enabled && $entity instanceof UserInterface) {
            if ($entity->getDateOfBirth()) {
                $age = $this->getAge($entity->getDateOfBirth());
                if ($ageBracket = $this->getUserHelper()->getAgeBracket($age, $this->context)) {
                    if (!$ageDemographics = $this->userAgeDemographicsManager->findOneBy(array('user'=>$entity))) {
                        $this->create($entity, $ageBracket);
                    } else {
                        $this->update($ageDemographics, $ageBracket);
                    }
                }
            }
        }
    }

    protected function create($user, $collection)
    {
        $ageDemographics = $this->userAgeDemographicsManager->create();
        $ageDemographics->setUser($user);
        $ageDemographics->setCollection($collection);
        $this->userAgeDemographicsManager->save($ageDemographics);
    }

    protected function update($ageDemographics, $collection)
    {
        $ageDemographics->setCollection($collection);
        $this->userAgeDemographicsManager->save($ageDemographics);
    }

    protected function getAge($bDate)
    {
        return date_diff($bDate, date_create('today'))->y;
    }

    /**
     * @return mixed
     */
    public function getUserHelper()
    {
        return $this->userHelper;
    }

    /**
     * @param mixed $userHelper
     */
    public function setUserHelper($userHelper)
    {
        $this->userHelper = $userHelper;
    }
}
