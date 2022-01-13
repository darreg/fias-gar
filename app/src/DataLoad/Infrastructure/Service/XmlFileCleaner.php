<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Shared\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Domain\XmlFile\Exception\CleanUpException;
use App\DataLoad\Domain\XmlFile\Service\XmlFileCleanerInterface;

class XmlFileCleaner implements XmlFileCleanerInterface
{
    private string $xmlDirectory;
    private XmlFileFinder $xmlFileFinder;

    public function __construct(
        string $xmlDirectory,
        XmlFileFinder $xmlFileFinder
    ) {
        $this->xmlDirectory = $xmlDirectory;
        $this->xmlFileFinder = $xmlFileFinder;
    }

    /**
     * @throws CleanUpException
     */
    public function clean(): void
    {
        if (!is_readable($this->xmlDirectory)) {
            throw new DirectoryIsNotReadableException("Invalid directory for xml files - {$this->xmlDirectory}");
        }

        exec('rm -rf ' . $this->xmlDirectory . '/*');

        $paths = $this->xmlFileFinder->getAllFindPath();
        if (\count($paths) !== 0) {
            throw new CleanUpException('The xml files have not been deleted');
        }
    }
}
