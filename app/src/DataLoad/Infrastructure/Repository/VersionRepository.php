<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Repository\VersionRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class VersionRepository implements VersionRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Version::class);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function find(string $id): ?Version
    {
        return $this->repo->find($id);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $id): Version
    {
        $version = $this->find($id);
        if ($version === null) {
            throw new EntityNotFoundException('Version is not found.');
        }

        return $version;
    }

    /**
     * @return array<int, Version>
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function findAll(): array
    {
        return $this->repo->findAll();
    }

    public function add(Version $version): void
    {
        $this->em->persist($version);
    }

    public function remove(Version $version): void
    {
        $this->em->remove($version);
    }
}
