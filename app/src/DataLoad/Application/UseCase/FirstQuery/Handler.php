<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FirstQuery;

use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class Handler implements QueryHandlerInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function __invoke(Query $query): ?ResponseInterface
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('admin')
            ->setMaxResults(1)
            ->executeQuery();

        $result = $queryBuilder->fetchAssociative();

        if (count($result) === 0) {
            return null;
        }

        return Response::fromArray($result);
    }
}
