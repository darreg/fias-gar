<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FirstQuery;

use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class Handler implements QueryHandlerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function __invoke(Query $query): ?ResponseInterface
    {
        $connection = $this->entityManager->getConnection();

        $query = $connection->createQueryBuilder()
            ->select('*')
            ->from('admin')
            ->setMaxResults(1)
            ->executeQuery();

        $result = $query->fetchAssociative();

        if (count($result) === 0) {
            return null;
        }

        return Response::fromArray($result);
    }
}
