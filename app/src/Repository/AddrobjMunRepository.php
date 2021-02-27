<?php

namespace App\Repository;

use App\Entity\AddrobjMun;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    public function findAddrobjectTree(int $objectid): array
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(AddrobjMun::class, 'ctp');

        $sql = 'WITH RECURSIVE child_to_parents AS (
                SELECT addrobj.* FROM v_addrobj_mun as addrobj
                WHERE addrobj.objectid = :objectid
                UNION ALL
                SELECT addrobj.* FROM v_addrobj_mun as addrobj, child_to_parents
                WHERE addrobj.objectid = child_to_parents.parentobjid
            ) SELECT ' . $rsm->generateSelectClause() . ' FROM child_to_parents AS ctp ORDER BY ctp.level';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('objectid', $objectid);

        return $query->getResult();
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
