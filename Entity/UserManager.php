<?php
namespace Rz\UserBundle\Entity;

use Sonata\UserBundle\Entity\UserManager as BaseUserManager;

class UserManager extends BaseUserManager
{

    public function fetchGenderCount() {
        $qb = $this->repository->createQueryBuilder('u');
        $em = $qb->getEntityManager();
        $query = $em->createQuery(sprintf('SELECT COUNT(u.gender) as num, u.gender  FROM %s u GROUP BY u.gender', $this->class));

        try {
            return $query->getResult();
        } catch(\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function fetchGenderCountTotal() {
        $qb = $this->repository->createQueryBuilder('u');
        $em = $qb->getEntityManager();

        $query = $em->createQuery(sprintf('SELECT COUNT(u.gender) as total  FROM %s u', $this->class))
                    ->setMaxResults(1);

        try {
            return $query->getSingleResult();
        } catch(\Exception $e) {
            return false;
        }
    }

}