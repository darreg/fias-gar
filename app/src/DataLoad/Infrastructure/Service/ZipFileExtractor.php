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

    private XmlFileFinder $xmlFileFinder;
    private string $zipDirectory;

    public function __construct(
        XmlFileFinder $xmlFileFinder,
        string $zipDirectory
    ) {
        $this->xmlFileFinder = $xmlFileFinder;
        $this->zipDirectory = $zipDirectory;
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @throws CleanUpException
     * @throws ZipFileNotFoundException
     * @throws NoFilesAfterUnpackingException
     * @throws RuntimeException
     */
    public function extract(string $versionId, string $fileName): void
    {
        if (!is_readable($this->zipDirectory)) {
            throw new DirectoryIsNotReadableException("Invalid directory for zip files - {$this->zipDirectory}");
        }

        if ($this->xmlFileFinder->versionDirectoryExists($versionId)) {
            throw new CleanUpException('The xml files have not been deleted');
        }

        $resultDirectory = $this->getResultDirectory($versionId);

        $this->unzip($fileName, $resultDirectory);

        $paths = $this->xmlFileFinder->getAllFindPathByVersion($versionId);
        if (\count($paths) === 0) {
            throw new NoFilesAfterUnpackingException('No xml files found after unpacking the archive');
        }

        self::setFilePermissions($resultDirectory);
    }

    /**
     * @throws ZipFileNotFoundException
     * @throws RuntimeException
     */
    private function unzip(string $fileName, string $resultDir): void
    {
        $zipFilePath = $this->zipDirectory . '/' . $fileName;
        if (!file_exists($zipFilePath)) {
            throw new ZipFileNotFoundException("Zip file '{$zipFilePath}' not found");
        }

        exec('unzip  ' . $zipFilePath . ' -d ' . $resultDir, $output, $exitCode);
        if ($exitCode !== 0) {
            throw new RuntimeException('Unzipping error');
        }
    }

    /**
     * @throws RuntimeException
     */
    private function getResultDirectory(string $versionId): string
    {
        $resultDir = $this->xmlFileFinder->getVersionDirectory($versionId);
        if (is_dir($resultDir)) {
            return $resultDir;
        }

        if (!mkdir($resultDir) && !is_dir($resultDir)) {
            throw new RuntimeException("Directory '{$resultDir}' was not created");
        }

        return $resultDir;
    }

    private static function setFilePermissions(string $resultDirectory): void
    {
        if (is_dir($resultDirectory . '/' . self::SCHEMAS_DIR)) {
            exec('chmod 777 ' . $resultDirectory . '/' . self::SCHEMAS_DIR);
        }

        exec('find ' . $resultDirectory . ' -type d -exec chmod 0755 {} +');
        exec('find ' . $resultDirectory . ' -type f -exec chmod 0644 {} +');
    }
}
