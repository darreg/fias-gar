<?php

namespace App\Repository;

use App\Entity\AddrobjAdm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AddrobjAdm|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddrobjAdm|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddrobjAdm[]    findAll()
 * @method AddrobjAdm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddrobjAdmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddrobjAdm::class);
    }

    // /**
    //  * @return AddrobjAdm[] Returns an array of AddrobjAdm objects
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
    public function findOneBySomeField($value): ?AddrobjAdm
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
