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

    public function fetchRegistrationCount($resultCount = 5) {

        $qb = $this->repository->createQueryBuilder('u');
        $em = $qb->getEntityManager();
        $query = $em->createQuery("SELECT COUNT(u.createdAt) as registrationCount, DATE_FORMAT(u.createdAt, '%Y-%m-%d') as registerDate  FROM ".$this->class." u GROUP BY registerDate ORDER BY u.createdAt ASC")
                    ->setMaxResults($resultCount);

        try {
            return $query->getResult();
        } catch(\Exception $e) {
            throw $e;
            return false;
        }
    }
	
	public function fetchRegistrationCount2($resultCount = 5,$dateFrom=null,$dateTo=null) {

        $qb = $this->repository->createQueryBuilder('u');
        $em = $qb->getEntityManager();
        $sql = "SELECT COUNT(u.createdAt) as registrationCount, DATE_FORMAT(u.createdAt, '%Y-%m-%d') as registerDate  FROM ".$this->class." u WHERE u.createdAt >= '".$dateFrom."' AND u.createdAt <= '".$dateTo."' GROUP BY registerDate ORDER BY u.createdAt ASC";
		$query = $em->createQuery($sql)
                    ->setMaxResults($resultCount);

        try {
            return $query->getResult();
        } catch(\Exception $e) {
            throw $e;
            return false;
        }
    }

}