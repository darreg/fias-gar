<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\NextVersion;

use App\Shared\Domain\Bus\Query\ResponseInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class Response implements ResponseInterface
{
    private ?string $versionId;

    public function __construct(?string $versionId)
    {
        $this->versionId = $versionId;
    }

    public function answer(): ?string
    {
        return $this->versionId;
    }
}
