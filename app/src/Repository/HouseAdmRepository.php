<?php

namespace App\Repository;

use App\Entity\HouseAdm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HouseAdm|null find($id, $lockMode = null, $lockVersion = null)
 * @method HouseAdm|null findOneBy(array $criteria, array $orderBy = null)
 * @method HouseAdm[]    findAll()
 * @method HouseAdm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HouseAdmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HouseAdm::class);
    }

    // /**
    //  * @return HouseAdm[] Returns an array of HouseAdm objects
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
    public function findOneBySomeField($value): ?HouseAdm
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
