<?php

declare(strict_types=1);

namespace App\Api\Shared\Infrastructure\Repository;

use App\Api\Shared\Domain\Entity\ExtHouse\ExtHouse;
use App\Api\Shared\Domain\Entity\ExtHouse\ExtHouseRepositoryInterface;
use App\Api\Shared\Domain\Entity\ExtHouse\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

class ExtHouseRepository implements ExtHouseRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    public function get(Id $id): ExtHouse
    {
        /** @var ExtHouse $extHouse */
        if (!$extHouse = $this->repo->find($id->getValue())) {
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