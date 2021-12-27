<?php

declare(strict_types=1);

namespace App\Api\Shared\Infrastructure\Repository;

use App\Api\Shared\Domain\Entity\ExtAddrobj\ExtAddrobj;
use App\Api\Shared\Domain\Entity\ExtAddrobj\ExtAddrobjRepositoryInterface;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

class ExtAddrobjRepository implements ExtAddrobjRepositoryInterface
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
        /** @var ExtAddrobj $extAddrobj */
        if (!$extAddrobj = $this->repo->find($objectid)) {
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