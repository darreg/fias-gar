<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Download;

use App\DataLoad\Domain\Shared\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Domain\XmlFile\Entity\XmlFile;
use App\DataLoad\Domain\XmlFile\Exception\CleanUpException;
use App\DataLoad\Domain\XmlFile\Exception\VersionNotRecognizedException;
use App\DataLoad\Domain\XmlFile\Service\XmlFileCleanerInterface;
use App\DataLoad\Domain\ZipFile\Exception\FileRemoveException;
use App\DataLoad\Domain\ZipFile\Exception\NoFilesAfterUnpackingException;
use App\DataLoad\Domain\ZipFile\Exception\ZipFileNotFoundException;
use App\DataLoad\Domain\ZipFile\Service\ZipFileDownloaderInterface;
use App\DataLoad\Domain\ZipFile\Service\ZipFileExtractorInterface;
use App\DataLoad\Domain\ZipFile\Service\ZipFileLoaderInterface;
use App\DataLoad\Domain\ZipFile\Service\ZipFileRotatorInterface;
use App\DataLoad\Infrastructure\Shared\ConfigParameterNotFoundException;
use RuntimeException;

class ZipFileDownloader implements ZipFileDownloaderInterface
{
    public const VERSION_PLACEHOLDER = '#version#';

    private ZipFileLoaderInterface $zipFileLoader;
    private ZipFileExtractorInterface $zipFileExtractor;
    private ZipFileRotatorInterface $zipFileRotator;
    private XmlFileCleanerInterface $xmlFileCleaner;
    private string $versionFormat;
    private string $urlFullXml;
    private string $urlDeltaXml;

    public function __construct(
        ZipFileLoaderInterface $zipFileLoader,
        ZipFileExtractorInterface $zipFileExtractor,
        ZipFileRotatorInterface $zipFileRotator,
        XmlFileCleanerInterface $xmlFileCleaner,
        string $versionFormat,
        string $urlFullXml,
        string $urlDeltaXml,
    ) {
        $this->zipFileLoader = $zipFileLoader;
        $this->zipFileExtractor = $zipFileExtractor;
        $this->zipFileRotator = $zipFileRotator;
        $this->xmlFileCleaner = $xmlFileCleaner;
        $this->versionFormat = $versionFormat;
        $this->urlFullXml = $urlFullXml;
        $this->urlDeltaXml = $urlDeltaXml;
    }

    /**
     * @throws ConfigParameterNotFoundException
     * @throws DirectoryIsNotReadableException
     * @throws CleanUpException
     * @throws ZipFileNotFoundException
     * @throws FileRemoveException
     * @throws NoFilesAfterUnpackingException
     * @throws VersionNotRecognizedException
     * @throws RuntimeException
     */
    public function downloadFull(string $versionId): void
    {
        $this->download($this->urlFullXml, $versionId);
    }

    /**
     * @throws ConfigParameterNotFoundException
     * @throws DirectoryIsNotReadableException
     * @throws CleanUpException
     * @throws ZipFileNotFoundException
     * @throws FileRemoveException
     * @throws NoFilesAfterUnpackingException
     * @throws VersionNotRecognizedException
     * @throws RuntimeException
     */
    public function downloadDelta(string $versionId): void
    {
        $this->download($this->urlDeltaXml, $versionId);
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @throws CleanUpException
     * @throws ZipFileNotFoundException
     * @throws FileRemoveException
     * @throws NoFilesAfterUnpackingException
     * @throws VersionNotRecognizedException
     * @throws RuntimeException
     */
    private function download(string $urlTemplate, string $versionId): void
    {
        $this->zipFileRotator->rotate();
        $fileName = $this->zipFileLoader->load(
            $this->buildUrl($urlTemplate, $versionId),
            $versionId
        );
        $this->xmlFileCleaner->clean();
        $this->zipFileExtractor->extract($fileName);
    }

    /**
     * @throws ConfigParameterNotFoundException
     * @throws VersionNotRecognizedException
     */
    private function buildUrl(string $template, string $versionId): string
    {
        return str_replace(
            self::VERSION_PLACEHOLDER,
            XmlFile::convertVersion($versionId, $this->versionFormat),
            $template
        );
    }
}
