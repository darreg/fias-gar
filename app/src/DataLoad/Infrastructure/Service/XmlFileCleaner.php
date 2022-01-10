<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Infrastructure\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Infrastructure\Exception\XmlFilesCleanUpException;

class XmlFileCleaner
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
     * @throws XmlFilesCleanUpException
     */
    public function clean(): void
    {
        if (!is_readable($this->xmlDirectory)) {
            throw new DirectoryIsNotReadableException("Invalid directory for xml files - {$this->xmlDirectory}");
        }

        exec('rm -rf ' . $this->xmlDirectory . '/*');

        $paths = $this->xmlFileFinder->getAllFindPath();
        if (\count($paths) !== 0) {
            throw new XmlFilesCleanUpException('The xml files have not been deleted');
        }
    }
}
