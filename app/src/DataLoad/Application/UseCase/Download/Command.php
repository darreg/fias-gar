<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Download;

use App\DataLoad\Application\Service\DownloaderInterface;
use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    private string $versionId;
    private string $type;

    /**
     * @param DownloaderInterface::TYPE_* $type
     */
    public function __construct(string $versionId, string $type = DownloaderInterface::TYPE_DELTA)
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
