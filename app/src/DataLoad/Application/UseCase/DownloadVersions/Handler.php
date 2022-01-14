<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\DownloadVersions;

use App\DataLoad\Application\Service\VersionDownloaderInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

class Handler implements CommandHandlerInterface
{
    private VersionDownloaderInterface $downloader;

    public function __construct(VersionDownloaderInterface $downloader)
    {
        $this->downloader = $downloader;
    }

    public function __invoke(Command $command)
    {
        $this->downloader->download();
    }
}
