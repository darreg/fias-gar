<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Download;

use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    public const TYPE_FULL = 'full';
    public const TYPE_DELTA = 'delta';

    private string $versionId;
    private string $type;

    public function __construct(string $versionId, string $type = self::TYPE_DELTA)
    {
        $this->versionId = $versionId;
        $this->type = $type;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
