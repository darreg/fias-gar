<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Table\Service;

use App\DataLoad\Domain\Table\Exception\TableColumnNotFoundException;
use RuntimeException;

interface TableColumnerInterface
{
    /**
     * @throws RuntimeException
     * @throws TableColumnNotFoundException
     * @return list<string>
     */
    public function getNames(string $tableName): array;
}
