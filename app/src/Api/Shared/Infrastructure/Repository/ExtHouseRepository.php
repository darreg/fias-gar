<?php

declare(strict_types=1);

namespace App\Api\Shared\Infrastructure\Repository;

use App\Api\Shared\Domain\Entity\ExtHouse\ExtHouse;
use App\Api\Shared\Domain\Entity\ExtHouse\ExtHouseRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class ExtHouseRepository implements ExtHouseRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    public function get(int $objectid): ExtHouse
    {
        /** @var ExtHouse $extHouse */
        if (!$extHouse = $this->repo->find($objectid)) {
            throw new DomainException('ExtHouse is not found.');
        }
        return $extHouse;
    }

    public function add(ExtHouse $extHouse): void
    {
        $this->em->persist($extHouse);
    }

    public function remove(ExtHouse $extHouse): void
    {
        $this->em->remove($extHouse);
    }
}
