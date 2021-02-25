<?php

namespace App\Repository;

use App\Entity\AddrobjMun;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AddrobjMun|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddrobjMun|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddrobjMun[]    findAll()
 * @method AddrobjMun[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddrobjMunRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddrobjMun::class);
    }

    // /**
    //  * @return AddrobjMun[] Returns an array of AddrobjMun objects
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
    public function findOneBySomeField($value): ?AddrobjMun
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
