<?php

namespace App\Repository;

use App\Entity\Producttype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Producttype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producttype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producttype[]    findAll()
 * @method Producttype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProducttypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Producttype::class);
    }

    // /**
    //  * @return TypeProduct[] Returns an array of TypeProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeProduct
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
