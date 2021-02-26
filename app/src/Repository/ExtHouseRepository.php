<?php

namespace App\Repository;

use App\Entity\ExtHouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtHouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtHouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtHouse[]    findAll()
 * @method ExtHouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtHouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtHouse::class);
    }

    // /**
    //  * @return ExtHouse[] Returns an array of ExtHouse objects
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
    public function findOneBySomeField($value): ?ExtHouse
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
