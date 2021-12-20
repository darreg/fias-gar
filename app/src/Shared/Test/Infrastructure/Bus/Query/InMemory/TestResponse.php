<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Query\InMemory;

use App\Shared\Domain\Bus\Query\ResponseInterface;

final class TestResponse implements ResponseInterface
{
    private int $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function number(): int
    {
        return $this->number;
    }
}
