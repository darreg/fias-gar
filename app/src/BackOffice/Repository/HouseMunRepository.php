<?php

namespace App\BackOffice\Repository;

use App\BackOffice\Entity\HouseMun;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HouseMun|null find($id, $lockMode = null, $lockVersion = null)
 * @method HouseMun|null findOneBy(array $criteria, array $orderBy = null)
 * @method HouseMun[]    findAll()
 * @method HouseMun[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-method list<HouseMun> findAll()
 * @psalm-method list<HouseMun> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-suppress PropertyNotSetInConstructor
 */
class HouseMunRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HouseMun::class);
    }
}
