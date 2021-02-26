<?php

namespace App\Repository;

use App\Entity\ExtAddrobjSynonym;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtAddrobjSynonym|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtAddrobjSynonym|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtAddrobjSynonym[]    findAll()
 * @method ExtAddrobjSynonym[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtAddrobjSynonymRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtAddrobjSynonym::class);
    }

    // /**
    //  * @return ExtAddrobjSynonym[] Returns an array of ExtAddrobjSynonym objects
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
    public function findOneBySomeField($value): ?ExtAddrobjSynonym
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
