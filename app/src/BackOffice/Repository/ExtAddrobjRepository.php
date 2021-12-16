<?php

namespace App\BackOffice\Repository;

use App\BackOffice\Entity\ExtAddrobj;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtAddrobj|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtAddrobj|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtAddrobj[]    findAll()
 * @method ExtAddrobj[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-method list<ExtAddrobj> findAll()
 * @psalm-method list<ExtAddrobj> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ExtAddrobjRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtAddrobj::class);
    }
}
