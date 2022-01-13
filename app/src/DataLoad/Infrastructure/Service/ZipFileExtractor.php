<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Shared\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Domain\XmlFile\Exception\CleanUpException;
use App\DataLoad\Domain\ZipFile\Exception\NoFilesAfterUnpackingException;
use App\DataLoad\Domain\ZipFile\Exception\ZipFileNotFoundException;
use App\DataLoad\Domain\ZipFile\Service\ZipFileExtractorInterface;
use RuntimeException;

class ZipFileExtractor implements ZipFileExtractorInterface
{
    public const SCHEMAS_DIR = 'Schemas';

    private string $zipDirectory;
    private string $xmlDirectory;
    private XmlFileFinder $xmlFileFinder;

    public function __construct(
        string $zipDirectory,
        string $xmlDirectory,
        XmlFileFinder $xmlFileFinder
    ) {
        $this->zipDirectory = $zipDirectory;
        $this->xmlDirectory = $xmlDirectory;
        $this->xmlFileFinder = $xmlFileFinder;
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @throws CleanUpException
     * @throws ZipFileNotFoundException
     * @throws NoFilesAfterUnpackingException
     * @throws RuntimeException
     */
    public function extract(string $fileName): void
    {
        if (!is_readable($this->zipDirectory)) {
            throw new DirectoryIsNotReadableException("Invalid directory for zip files - {$this->zipDirectory}");
        }

        $paths = $this->xmlFileFinder->getAllFindPath();
        if (\count($paths) !== 0) {
            throw new CleanUpException('The xml files have not been deleted');
        }

        $filePath = $this->zipDirectory . '/' . $fileName;
        if (!file_exists($filePath)) {
            throw new ZipFileNotFoundException("Zip file '{$filePath}' not found");
        }

        exec('unzip  ' . $filePath . ' -d ' . $this->xmlDirectory, $output, $exitCode);
        if ($exitCode !== 0) {
            throw new RuntimeException('Unzipping error');
        }

        $paths = $this->xmlFileFinder->getAllFindPath();
        if (\count($paths) === 0) {
            throw new NoFilesAfterUnpackingException('No xml files found after unpacking the archive');
        }

        exec('chmod 777 ' . $this->xmlDirectory . '/' . self::SCHEMAS_DIR);
        exec('chmod -R 644 ' . $this->xmlDirectory . '/*.XML');
    }
}
