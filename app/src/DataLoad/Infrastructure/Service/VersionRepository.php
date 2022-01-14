<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Entity\VersionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class VersionRepository implements VersionRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
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
     * @throws DomainException
     */
    public function get(string $id): Version
    {
        /** @var Version|null $version */
        $version = $this->repo->find($id);

        if ($version === null) {
            throw new DomainException('Version is not found.');
        }

        return $version;
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
