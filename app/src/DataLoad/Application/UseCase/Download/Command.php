<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Download;

use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    private string $type;
    private string $versionId;

    /**
     * @param Version::TYPE_* $type
     */
    public function __construct(string $type, string $versionId)
    {
        $this->type = $type;
        $this->versionId = $versionId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
    }
}
