<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FirstQuery;

use App\Shared\Domain\Bus\Query\QueryInterface;

class Query implements QueryInterface
{
    private string $id;
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
