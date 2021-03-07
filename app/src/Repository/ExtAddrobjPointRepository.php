<?php

namespace App\Repository;

use App\Entity\ExtAddrobjPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtAddrobjPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtAddrobjPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtAddrobjPoint[]    findAll()
 * @method ExtAddrobjPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-method list<ExtAddrobjPoint> findAll()
 * @psalm-method list<ExtAddrobjPoint> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ExtAddrobjPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtAddrobjPoint::class);
    }
}
