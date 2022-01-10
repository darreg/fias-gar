<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Download;

use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    public const TYPE_FULL = 'full';
    public const TYPE_DELTA = 'delta';

    private string $version;
    private string $type;

    public function __construct(string $version, string $type)
    {
        $this->version = $version;
        $this->type = $type;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
