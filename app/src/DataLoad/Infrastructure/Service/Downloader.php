<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Service\DownloaderInterface;
use App\DataLoad\Domain\Entity\File;
use App\DataLoad\Domain\Exception\VersionNotRecognizedException;
use App\DataLoad\Infrastructure\Exception\ConfigParameterNotFoundException;
use App\DataLoad\Infrastructure\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Infrastructure\Exception\NoFilesAfterUnpackingException;
use App\DataLoad\Infrastructure\Exception\XmlFilesCleanUpException;
use App\DataLoad\Infrastructure\Exception\ZipFileNotFoundException;
use RuntimeException;

class Downloader implements DownloaderInterface
{
    public const VERSION_PLACEHOLDER = '#version#';
    public const VERSION_FORMAT = 'fias_url_version_format';
    public const FULL_FIAS_DB_URL = 'fias_url_full_xml';
    public const DELTA_FIAS_DB_URL = 'fias_url_delta_xml';

    private ZipFileLoader $zipFileLoader;
    private ZipFileExtractor $zipFileExtractor;
    private XmlFileCleaner $xmlFileCleaner;
    private ParameterStorage $parameterStorage;

    public function __construct(
        ZipFileLoader $zipFileLoader,
        ZipFileExtractor $zipFileExtractor,
        XmlFileCleaner $xmlFileCleaner,
        ParameterStorage $parameterStorage
    ) {
        $this->zipFileLoader = $zipFileLoader;
        $this->zipFileExtractor = $zipFileExtractor;
        $this->xmlFileCleaner = $xmlFileCleaner;
        $this->parameterStorage = $parameterStorage;
    }

    /**
     * @throws ConfigParameterNotFoundException
     * @throws DirectoryIsNotReadableException
     * @throws XmlFilesCleanUpException
     * @throws ZipFileNotFoundException
     * @throws NoFilesAfterUnpackingException
     * @throws VersionNotRecognizedException
     * @throws RuntimeException
     */
    public function downloadFull(string $versionId): void
    {
        $urlTemplate = $this->parameterStorage->getParameter(self::FULL_FIAS_DB_URL);
        $this->download($urlTemplate, $versionId);
    }

    /**
     * @throws ConfigParameterNotFoundException
     * @throws DirectoryIsNotReadableException
     * @throws XmlFilesCleanUpException
     * @throws ZipFileNotFoundException
     * @throws NoFilesAfterUnpackingException
     * @throws VersionNotRecognizedException
     * @throws RuntimeException
     */
    public function downloadDelta(string $versionId): void
    {
        $urlTemplate = $this->parameterStorage->getParameter(self::DELTA_FIAS_DB_URL);
        $this->download($urlTemplate, $versionId);
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @throws XmlFilesCleanUpException
     * @throws ZipFileNotFoundException
     * @throws NoFilesAfterUnpackingException
     * @throws VersionNotRecognizedException
     * @throws RuntimeException
     */
    private function download(string $urlTemplate, string $versionId): void
    {
        $fileName = $this->zipFileLoader->load(
            $this->buildUrl($urlTemplate, $versionId)
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
        $versionPlaceholder = $this->parameterStorage->getParameter(self::VERSION_PLACEHOLDER);
        $versionFormat =$this->parameterStorage->getParameter(self::VERSION_FORMAT);

        return str_replace(
            $versionPlaceholder,
            File::convertVersion($versionId, $versionFormat),
            $template
        );
    }
}
