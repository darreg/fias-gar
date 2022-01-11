<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Service\ZipFileDownloaderInterface;
use App\DataLoad\Domain\Entity\File;
use App\DataLoad\Domain\Exception\VersionNotRecognizedException;
use App\DataLoad\Infrastructure\Exception\ConfigParameterNotFoundException;
use App\DataLoad\Infrastructure\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Infrastructure\Exception\NoFilesAfterUnpackingException;
use App\DataLoad\Infrastructure\Exception\XmlFilesCleanUpException;
use App\DataLoad\Infrastructure\Exception\ZipFileDeletionException;
use App\DataLoad\Infrastructure\Exception\ZipFileNotFoundException;
use RuntimeException;

class ZipFileDownloader implements ZipFileDownloaderInterface
{
    public const VERSION_PLACEHOLDER = '#version#';

    private ZipFileLoader $zipFileLoader;
    private ZipFileExtractor $zipFileExtractor;
    private ZipFileRotator $zipFileRotator;
    private XmlFileCleaner $xmlFileCleaner;
    private string $versionFormat;
    private string $urlFullXml;
    private string $urlDeltaXml;

    public function __construct(
        ZipFileLoader $zipFileLoader,
        ZipFileExtractor $zipFileExtractor,
        ZipFileRotator $zipFileRotator,
        XmlFileCleaner $xmlFileCleaner,
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
     * @throws XmlFilesCleanUpException
     * @throws ZipFileNotFoundException
     * @throws ZipFileDeletionException
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
     * @throws XmlFilesCleanUpException
     * @throws ZipFileNotFoundException
     * @throws ZipFileDeletionException
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
     * @throws XmlFilesCleanUpException
     * @throws ZipFileNotFoundException
     * @throws ZipFileDeletionException
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
            File::convertVersion($versionId, $this->versionFormat),
            $template
        );
    }
}
