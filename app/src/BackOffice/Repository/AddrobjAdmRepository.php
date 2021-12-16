<?php

namespace App\BackOffice\Repository;

use App\BackOffice\Entity\AddrobjAdm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AddrobjAdm|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddrobjAdm|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddrobjAdm[]    findAll()
 * @method AddrobjAdm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-method list<AddrobjAdm> findAll()
 * @psalm-method list<AddrobjAdm> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AddrobjAdmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddrobjAdm::class);
    }

    /**
     * @param int $objectid
     * @return AddrobjAdm[]
     *
     * @psalm-return array<int, AddrobjAdm>
     */
    public function findAddrobjectTree(int $objectid): array
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(AddrobjAdm::class, 'ctp');

        $sql = 'WITH RECURSIVE child_to_parents AS (
                SELECT addrobj.* FROM v_addrobj_adm as addrobj
                WHERE addrobj.objectid = :objectid
                UNION ALL
                SELECT addrobj.* FROM v_addrobj_adm as addrobj, child_to_parents
                WHERE addrobj.objectid = child_to_parents.parentobjid
            ) SELECT ' . $rsm->generateSelectClause() . ' FROM child_to_parents AS ctp ORDER BY ctp.level';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('objectid', $objectid);

        /**
         * @var AddrobjAdm[] $result
         * @psalm-var array<int, AddrobjAdm> $result
         */
        $result = $query->getResult();

        return $result;
    }
}
