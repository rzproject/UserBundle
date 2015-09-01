<?php

namespace Rz\UserBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;

class UserAuthenticationLogsManager extends BaseEntityManager
{
    public function fetchUserLogsByDayCount($date,$type = 'login') {
        $query = $this->getRepository()
            ->createQueryBuilder('al')
            ->select("count(al.logDate) as logDateCount, DATE_FORMAT(al.logDate, '%Y-%m-%d') as logDate")
            ->where('al.type = :type')
            ->andWhere('al.logDate >= :logDate')
            ->groupBy("logDate")
            ->setParameters(array('type'=>$type, 'logDate'=>$date))
            ->getQuery()
            ->useResultCache(true, 3600);

        try {
            return $query->getArrayResult();
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
