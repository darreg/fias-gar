<?php

namespace App\Repository;

use App\Entity\ExtAddrobj;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtAddrobj|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtAddrobj|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtAddrobj[]    findAll()
 * @method ExtAddrobj[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtAddrobjRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtAddrobj::class);
    }

    // /**
    //  * @return ExtAddrobj[] Returns an array of ExtAddrobj objects
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
    public function findOneBySomeField($value): ?ExtAddrobj
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
