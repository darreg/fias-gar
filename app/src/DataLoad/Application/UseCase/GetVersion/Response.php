<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\GetVersion;

use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Query\ResponseInterface;

class Response implements ResponseInterface
{
    private ?Version $version;

    public function __construct(?Version $version)
    {
        $this->version = $version;
    }

    public function answer(): ?Version
    {
        return $this->version;
    }
}
