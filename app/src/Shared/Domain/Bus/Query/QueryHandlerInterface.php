<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Query;

interface QueryHandlerInterface
{
    public function __invoke(QueryInterface $command): void;
}