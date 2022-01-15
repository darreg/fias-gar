<?php

declare(strict_types=1);

namespace App\Api\Shared\Infrastructure\Repository;

use App\Api\Shared\Domain\ExtAddrobj\Entity\ExtAddrobj;
use App\Api\Shared\Domain\ExtAddrobj\Repository\ExtAddrobjRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ExtAddrobjRepository implements ExtAddrobjRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(ExtAddrobj::class);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $objectid): ExtAddrobj
    {
        /** @var ExtAddrobj|null $extAddrobj */
        $extAddrobj = $this->repo->find($objectid);

        if ($extAddrobj === null) {
            throw new EntityNotFoundException('ExtAddrobj is not found.');
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
