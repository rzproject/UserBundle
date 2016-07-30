<?php


namespace Rz\UserBundle\Entity;

use Sonata\UserBundle\Entity\UserManager as BaseUserManager;

class UserManager extends BaseUserManager
{
    public function fetchGenderCount() {
        $qb = $this->repository->createQueryBuilder('u');
        $em = $qb->getEntityManager();
        $query = $em->createQuery(sprintf('SELECT COUNT(u.gender) as total, u.gender  FROM %s u GROUP BY u.gender ORDER BY u.gender DESC', $this->class))
                    ->useResultCache(true, 3600);

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
            ->useResultCache(true, 3600)
            ->setMaxResults(1);

        try {
            return $query->getSingleResult();
        } catch(\Exception $e) {
            return false;
        }
    }
}
