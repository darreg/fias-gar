<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Download;

use App\DataLoad\Application\Service\DownloaderInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

class Handler implements CommandHandlerInterface
{
    private DownloaderInterface $downloader;

    public function __construct(
        DownloaderInterface $downloader
    ) {
        $this->downloader = $downloader;
    }

    public function __invoke(Command $command)
    {
        $versionId = $command->getVersionId();
        switch ($command->getType()) {
            case Command::TYPE_FULL:
                $this->downloader->downloadFull($versionId);
                break;
            case Command::TYPE_DELTA:
                $this->downloader->downloadDelta($versionId);
                break;
        }
    }
}
