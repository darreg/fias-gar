<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Extract;

use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    private string $versionId;
    private string $type;

    /**
     * @param Version::TYPE_* $type
     */
    public function __construct(string $type, string $versionId)
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
