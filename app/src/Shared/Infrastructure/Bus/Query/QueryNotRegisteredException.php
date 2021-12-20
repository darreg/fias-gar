<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\QueryInterface;
use RuntimeException;

final class QueryNotRegisteredException extends RuntimeException
{
    public function __construct(QueryInterface $query)
    {
        $queryClass = \get_class($query);

        parent::__construct("The query <$queryClass> hasn't a query handler associated");
    }
}
