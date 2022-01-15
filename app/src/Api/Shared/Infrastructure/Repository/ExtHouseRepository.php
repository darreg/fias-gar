<?php

declare(strict_types=1);

namespace App\Api\Shared\Infrastructure\Repository;

use App\Api\Shared\Domain\ExtHouse\Entity\ExtHouse;
use App\Api\Shared\Domain\ExtHouse\Repository\ExtHouseRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ExtHouseRepository implements ExtHouseRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(ExtHouse::class);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $objectid): ExtHouse
    {
        /** @var ExtHouse|null $extHouse */
        $extHouse = $this->repo->find($objectid);

        if ($extHouse === null) {
            throw new EntityNotFoundException('ExtHouse is not found.');
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
