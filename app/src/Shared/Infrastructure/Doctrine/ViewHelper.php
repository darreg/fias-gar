<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class ViewHelper
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function refresh(string $viewName): void
    {
        $this->connection->executeStatement(
            sprintf('REFRESH MATERIALIZED VIEW %s', $viewName)
        );
    }
}
