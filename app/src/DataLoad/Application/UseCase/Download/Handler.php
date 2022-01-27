<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Download;

use App\DataLoad\Application\Service\DataDownloaderInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use RuntimeException;

class Handler implements CommandHandlerInterface
{
    private DataDownloaderInterface $downloader;
    private string $zipDirectory;

    public function __construct(
        DataDownloaderInterface $downloader,
        string $zipDirectory
    ) {
        $this->downloader = $downloader;
        $this->zipDirectory = $zipDirectory;
    }

    public function __invoke(Command $command)
    {
        self::checkZipDirectory($this->zipDirectory);

        /** @psalm-suppress ArgumentTypeCoercion */
        $this->downloader->download($command->getType(), $command->getVersionId());
    }

    private static function checkZipDirectory(string $zipDirectory): void
    {
        if (
            !is_dir($zipDirectory) &&
            !mkdir($concurrentDirectory = $zipDirectory, 0o777, true) &&
            !is_dir($concurrentDirectory)
        ) {
            throw new RuntimeException("Directory '{$concurrentDirectory}' was not created");
        }
    }
}
