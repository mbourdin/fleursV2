<?php
/**
 * Created by PhpStorm.
 * User: mbourdin
 * Date: 28/03/2019
 * Time: 11:53
 */
namespace App\Repository;
use Doctrine\ORM\EntityRepository;
use DateTimeImmutable;
class SaleRepository extends EntityRepository
{
    /**
     * @param int $days
     * @param int $interval
     * @return array
     * @throws \Exception
     */
    public function findAllByDaysFromNow(int $days,int $interval=7)
    {   if ($interval<=0){throw new \InvalidArgumentException("intervalle de temps invalide");}
        $now=new DateTimeImmutable();
        $begin=$now->modify(($days-1)." day");
        $end=$begin->modify($interval." day");


        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT p FROM App\Entity\Sale p WHERE p.date >= :begin AND p.date <= :end');
        $query->setParameter('begin', $begin->format("Y-m-d h:i:s"));
        $query->setParameter('end', $end->format("Y-m-d h:i:s"));
        return $query->getResult();
    }
}