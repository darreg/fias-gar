<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Extract;

use App\DataLoad\Application\Service\DataExtractorInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use RuntimeException;

class Handler implements CommandHandlerInterface
{
    private DataExtractorInterface $extractor;
    private string $xmlDirectory;

    public function __construct(
        DataExtractorInterface $extractor,
        string $xmlDirectory
    ) {
        $this->extractor = $extractor;
        $this->xmlDirectory = $xmlDirectory;
    }

    public function __invoke(Command $command)
    {
        self::checkXmlDirectory($this->xmlDirectory);

        /** @psalm-suppress ArgumentTypeCoercion */
        $this->extractor->extract($command->getType(), $command->getVersionId());
    }

    private static function checkXmlDirectory(string $xmlDirectory): void
    {
        if (
            !is_dir($xmlDirectory) &&
            !mkdir($concurrentDirectory = $xmlDirectory, 0o777, true) &&
            !is_dir($concurrentDirectory)
        ) {
            throw new RuntimeException("Directory '{$concurrentDirectory}' was not created");
        }
    }
}
