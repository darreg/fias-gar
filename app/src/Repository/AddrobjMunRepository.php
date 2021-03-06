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
 * @psalm-method list<AddrobjMun> findAll()
 * @psalm-method list<AddrobjMun> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AddrobjMunRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddrobjMun::class);
    }

    /**
     * @param int $objectid
     * @return AddrobjMun[]
     *
     * @psalm-return array<int, AddrobjMun>
     */
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

        /**
         * @var AddrobjMun[] $result
         * @psalm-var array<int, AddrobjMun> $result
         */
        $result = $query->getResult();

        return $result;
    }
}
