<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Counter\Entity\Counter;
use App\DataLoad\Domain\Counter\Repository\CounterRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class CounterRepository implements CounterRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Counter::class);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function find(string $key): ?Counter
    {
        return $this->repo->find($key);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $key): Counter
    {
        $version = $this->find($key);
        if ($version === null) {
            throw new EntityNotFoundException('Counter is not found.');
        }

        return $version;
    }

    /**
     * @return array<int, Counter>
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function findAll(): array
    {
        return $this->repo->findAll();
    }

    public function persist(Counter $counter): void
    {
        $this->em->persist($counter);
    }

    public function remove(Counter $counter): void
    {
        $this->em->remove($counter);
    }
}
