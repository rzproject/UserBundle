<?php

namespace Rz\UserBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;

class UserAuthenticationLogsManager extends BaseEntityManager
{
    public function fetchUserLogsByDayCountGender($date,$type = 'login') {
        $query = $this->getRepository()
            ->createQueryBuilder('al')
            ->select("DISTINCT(u.id), count(al.logDate) as logDateCount, DATE_FORMAT(al.logDate, '%Y-%m-%d') as logDate, u.gender")
            ->leftJoin('al.user', 'u')
            ->where('al.type = :type')
            ->andWhere('al.logDate >= :startDate')
            ->andWhere('al.logDate <= :endDate')
            ->groupBy("logDate, u.gender")
            ->orderBy('u.gender', 'DESC')
            ->setParameters(array('type'=>$type, 'startDate'=>$date, 'endDate'=>new \DateTime()))
            ->getQuery()
            ->useResultCache(true, 3600);

        try {
            return $query->getArrayResult();
        } catch(\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function fetchUserLogsByDayCount($date,$type = 'login') {
        $query = $this->getRepository()
            ->createQueryBuilder('al')
            ->select("DISTINCT(u.id), count(al.logDate) as logDateCount, DATE_FORMAT(al.logDate, '%Y-%m-%d') as logDate")
            ->leftJoin('al.user', 'u')
            ->where('al.type = :type')
            ->andWhere('al.logDate >= :startDate')
            ->andWhere('al.logDate <= :endDate')
            ->groupBy("logDate")
            ->setParameters(array('type'=>$type, 'startDate'=>$date, 'endDate'=>new \DateTime()))
            ->getQuery()
            ->useResultCache(true, 3600);

        try {
            return $query->getArrayResult();
        } catch(\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function fetchCurrentLoggedUser($type = 'login') {

        $today = new \DateTime();
        $today->setTime(0,0);

        $query = $this->getRepository()
            ->createQueryBuilder('al')
            ->select("DISTINCT(u.id),count(al.logDate) as total")
            ->leftJoin('al.user', 'u')
            ->where('al.type = :type')
            ->andWhere('al.logDate >= :startDate')
            ->andWhere('al.logDate <= :endDate')
            ->setParameters(array('type'=>$type, 'startDate'=>$today, 'endDate'=>new \DateTime()))
            ->setMaxResults(1)
            ->getQuery()
            ->useResultCache(true, 3600);

        try {
            return $query->getSingleResult();
        } catch(\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function fetchAgeBracketCountTotal() {
        $query = $this->getRepository()
            ->createQueryBuilder('ad')
            ->select('count(ad.id) as ageBracketTotal')
            ->setMaxResults(1)
            ->getQuery()
            ->useResultCache(true, 3600);

        try {
            return $query->getSingleResult();
        } catch(\Exception $e) {
            return false;
        }
    }
}
