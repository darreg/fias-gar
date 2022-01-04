<?php

declare(strict_types=1);

namespace App\Api\Shared\Infrastructure\Repository;

use App\Api\Shared\Domain\Entity\ExtAddrobj\ExtAddrobj;
use App\Api\Shared\Domain\Entity\ExtAddrobj\ExtAddrobjRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class ExtAddrobjRepository implements ExtAddrobjRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    public function get(int $objectid): ExtAddrobj
    {
        /** @var ExtAddrobj|null $extAddrobj */
        $extAddrobj = $this->repo->find($objectid);
        
        if ($extAddrobj === null) {
            throw new DomainException('ExtAddrobj is not found.');
        }
        
        return $extAddrobj;
    }

    public function add(ExtAddrobj $extAddrobj): void
    {
        $this->em->persist($extAddrobj);
    }

    public function remove(ExtAddrobj $extAddrobj): void
    {
        $this->em->remove($extAddrobj);
    }
}
