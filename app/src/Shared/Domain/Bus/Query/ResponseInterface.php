<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Query;

interface ResponseInterface
{
    public function answer(): mixed;
}
