<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Repository\ImportRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ImportRepository implements ImportRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Import::class);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function find(string $key): ?Import
    {
        return $this->repo->find($key);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $key): Import
    {
        $version = $this->find($key);
        if ($version === null) {
            throw new EntityNotFoundException('Import is not found.');
        }

        return $version;
    }

    /**
     * @return array<int, Import>
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function findAll(): array
    {
        return $this->repo->findAll();
    }

    public function persist(Import $import): void
    {
        $this->em->persist($import);
    }

    public function remove(Import $import): void
    {
        $this->em->remove($import);
    }
}
