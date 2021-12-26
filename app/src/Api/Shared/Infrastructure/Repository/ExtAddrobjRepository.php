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

    public function get(Id $id): ExtAddrobj
    {
        /** @var ExtAddrobj $extHouse */
        if (!$extHouse = $this->repo->find($id->getValue())) {
            throw new DomainException('ExtAddrobj is not found.');
        }
        return $extHouse;
    }

    public function add(ExtAddrobj $extHouse): void
    {
        $this->em->persist($extHouse);
    }

    public function remove(ExtAddrobj $extHouse): void
    {
        $this->em->remove($extHouse);
    }
}