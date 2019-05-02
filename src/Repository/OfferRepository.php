<?php
/**
 * Created by PhpStorm.
 * User: mbourdin
 * Date: 28/03/2019
 * Time: 11:53
 */
namespace App\Repository;
use Doctrine\ORM\EntityRepository;
class OfferRepository extends EntityRepository
{
    public function findByNameLikeAndActiveTrue($name)
{   $em=$this->getEntityManager();
    $query=$em->createQuery('SELECT p FROM App\Entity\Offer p WHERE p.name LIKE :name AND p.active=true');
    $query->setParameter('name', "%".$name."%");
    return $query->getResult();
}
}