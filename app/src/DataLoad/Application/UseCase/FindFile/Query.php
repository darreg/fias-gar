<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FindFile;

use App\Shared\Domain\Bus\Query\QueryInterface;

class Query implements QueryInterface
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
