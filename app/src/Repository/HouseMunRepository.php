<?php

namespace App\Repository;

use App\Entity\HouseMun;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HouseMun|null find($id, $lockMode = null, $lockVersion = null)
 * @method HouseMun|null findOneBy(array $criteria, array $orderBy = null)
 * @method HouseMun[]    findAll()
 * @method HouseMun[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HouseMunRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HouseMun::class);
    }

    // /**
    //  * @return HouseMun[] Returns an array of HouseMun objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HouseMun
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
