<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\DownloadXmlFiles;

use App\DataLoad\Application\Service\XmlDownloaderInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

class Handler implements CommandHandlerInterface
{
    private XmlDownloaderInterface $downloader;

    public function __construct(XmlDownloaderInterface $downloader)
    {
        $this->downloader = $downloader;
    }

    public function __invoke(Command $command)
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        $this->downloader->download($command->getType(), $command->getVersionId());
    }
}
