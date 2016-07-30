<?php

namespace Rz\UserBundle\Component\Helper;

use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\CoreBundle\Model\ManagerInterface;

class UserHelper
{
    protected $collectionManager;
    /**
     * @return mixed
     */
    public function getCollectionManager()
    {
        return $this->collectionManager;
    }

    /**
     * @param mixed $collectionManager
     */
    public function setCollectionManager(ManagerInterface $collectionManager)
    {
        $this->collectionManager = $collectionManager;
    }

    public function getAgeBracket($age = null, $context = null)
    {
        if ($age && $context) {
            $ageBrackets = $this->collectionManager->findBy(array('context'=>$context, 'enabled'=>true));
            foreach ($ageBrackets as $bracket) {
                if ($settings = $bracket->getSettings()) {
                    if (array_key_exists('min', $settings) && array_key_exists('max', $settings)) {
                        if (((int)$settings['min']) <= $age && (((int)$settings['max']) >= $age)) {
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
