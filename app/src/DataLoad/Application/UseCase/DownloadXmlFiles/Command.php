<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\DownloadXmlFiles;

use App\DataLoad\Application\Service\XmlDownloaderInterface;
use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    private string $versionId;
    private string $type;

    /**
     * @param XmlDownloaderInterface::TYPE_* $type
     */
    public function __construct(string $versionId, string $type = XmlDownloaderInterface::TYPE_DELTA)
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
