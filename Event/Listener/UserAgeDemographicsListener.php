<?php



namespace Rz\UserBundle\Event\Listener;

use Sonata\CoreBundle\Model\ManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Sonata\UserBundle\Model\UserInterface;


class UserAgeDemographicsListener
{
    protected $collectionManager;
    protected $userAgeDemographicsManager;

    public function __construct(ManagerInterface $userAgeDemographicsManager, ManagerInterface $collectionManager)
    {
        $this->userAgeDemographicsManager = $userAgeDemographicsManager;
        $this->collectionManager = $collectionManager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
//        $entity = $args->getEntity();
//        $entityManager = $args->getEntityManager();
//
//        // perhaps you only want to act on some "Product" entity
//        if ($entity instanceof UserInterface) {
//            dump($entity);
//            die();
//            // ... do something with the Product
//        }
    }


    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof UserInterface) {
            //get
            if($entity->getDateOfBirth()) {
                $age = $this->getAge($entity->getDateOfBirth());
                if($ageBracket = $this->getAgeBracket($age)) {
                    if(!$ageDemographics = $this->userAgeDemographicsManager->findOneBy(array('user'=>$entity))) {
                        $ageDemographics = $this->userAgeDemographicsManager->create();
                        $ageDemographics->setUser($entity);
                        $ageDemographics->setCollection($ageBracket);
                    } else {
                        $ageDemographics->setCollection($ageBracket);
                    }
                    $this->userAgeDemographicsManager->save($ageDemographics);
                }
            }
        }
    }

    protected function getAge($bDate) {
        return date_diff($bDate, date_create('today'))->y;
    }

    protected function getAgeBracket($age = null) {
        if($age) {
            $ageBrackets = $this->collectionManager->findByContextId('rz-user-age-demographics');
            foreach ($ageBrackets as $bracket) {
                if($settings = $bracket->getSettings()) {
                    if(array_key_exists('min', $settings) && array_key_exists('max', $settings)) {
                        if(((int)$settings['min']) <= $age && (((int)$settings['max']) >= $age)) {
                            return $bracket;
                        }
                    }
                }
            }
        } else {
            return null;
        }
    }
}
