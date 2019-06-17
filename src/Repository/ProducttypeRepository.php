<?php

namespace App\Repository;

use App\Entity\ProductType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductType[]    findAll()
 * @method ProductType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProducttypeRepository extends ServiceEntityRepository
{
    /**
     * ProducttypeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductType::class);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function findOneByNameLikeAndActiveTrue($name)
    {   $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT p FROM App\Entity\ProductType p WHERE p.name LIKE :name AND p.active=true');
        $query->setParameter('name', "%".$name."%");
        return $query->getResult()[0];
    }
}
