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
 * @psalm-method list<ExtAddrobjSynonym> findAll()
 * @psalm-method list<ExtAddrobjSynonym> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ExtAddrobjSynonymRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtAddrobjSynonym::class);
    }
}
