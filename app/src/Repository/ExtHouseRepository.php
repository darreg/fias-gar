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
 * @psalm-method list<ExtHouse> findAll()
 * @psalm-method list<ExtHouse> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtHouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtHouse::class);
    }
}
