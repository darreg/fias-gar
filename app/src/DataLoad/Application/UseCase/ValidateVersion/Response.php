<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ValidateVersion;

use App\Shared\Domain\Bus\Query\ResponseInterface;

class Response implements ResponseInterface
{
    private bool $isValidForLoad;

    public function __construct(bool $isValidForLoad)
    {
        $this->isValidForLoad = $isValidForLoad;
    }

    public function answer(): bool
    {
        return $this->isValidForLoad;
    }
}
