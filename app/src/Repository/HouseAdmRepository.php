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
 * @psalm-method list<HouseAdm> findAll()
 * @psalm-method list<HouseAdm> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-suppress PropertyNotSetInConstructor
 */
class HouseAdmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HouseAdm::class);
    }
}
