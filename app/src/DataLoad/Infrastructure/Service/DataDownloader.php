<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Exception\DownloadException;
use App\DataLoad\Application\Service\DataDownloaderInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Service\LoadTryIncrementorInterface;
use App\DataLoad\Domain\XmlFile\Entity\XmlFile;
use App\DataLoad\Domain\XmlFile\Exception\VersionNotRecognizedException;
use App\DataLoad\Domain\ZipFile\Exception\FileNotAvailableException;
use App\DataLoad\Domain\ZipFile\Service\ZipFileLoaderInterface;
use App\DataLoad\Domain\ZipFile\Service\ZipFileNamerInterface;
use App\DataLoad\Domain\ZipFile\Service\ZipFileRotatorInterface;
use App\DataLoad\Infrastructure\Exception\ConfigParameterNotFoundException;
use LogicException;
use RuntimeException;

class DataDownloader implements DataDownloaderInterface
{
    public const VERSION_PLACEHOLDER = '#version#';

    private ZipFileNamerInterface $zipFileService;
    private ZipFileLoaderInterface $zipFileLoader;
    private ZipFileRotatorInterface $zipFileRotator;
    private LoadTryIncrementorInterface $loadTryIncrementor;
    private string $versionFormat;
    private string $urlFullXml;
    private string $urlDeltaXml;

    public function __construct(
        ZipFileNamerInterface $zipFileService,
        ZipFileLoaderInterface $zipFileLoader,
        ZipFileRotatorInterface $zipFileRotator,
        LoadTryIncrementorInterface $loadTryIncrementor,
        string $versionFormat,
        string $urlFullXml,
        string $urlDeltaXml,
    ) {
        $this->zipFileService = $zipFileService;
        $this->zipFileLoader = $zipFileLoader;
        $this->zipFileRotator = $zipFileRotator;
        $this->loadTryIncrementor = $loadTryIncrementor;
        $this->versionFormat = $versionFormat;
        $this->urlFullXml = $urlFullXml;
        $this->urlDeltaXml = $urlDeltaXml;
    }

    /**
     * @throws DownloadException
     * @throws RuntimeException
     */
    public function full(string $versionId): void
    {
        $this->download(Version::TYPE_FULL, $versionId);
    }

    /**
     * @throws DownloadException
     * @throws RuntimeException
     */
    public function delta(string $versionId): void
    {
        $this->download(Version::TYPE_DELTA, $versionId);
    }

    /**
     * @param Version::TYPE_* $type
     * @throws DownloadException
     * @throws RuntimeException
     */
    public function download(string $type, string $versionId): void
    {
        $urlTemplate = match ($type) {
            Version::TYPE_FULL => $this->urlFullXml,
            Version::TYPE_DELTA => $this->urlDeltaXml,
            default => throw new DownloadException("Incorrect download type '{$type}'"),
        };

        try {
            $this->zipFileRotator->rotate();
            $fileName = $this->zipFileService->getFileName($type, $versionId);
            $this->zipFileLoader->load($this->buildUrl($urlTemplate, $versionId), $fileName);
        } catch (FileNotAvailableException $e) {
            $this->loadTryIncrementor->increment($type, $versionId);
            throw new RuntimeException($e->getMessage());
        } catch (LogicException $e) {
            throw new DownloadException("Error loading the {$type} database version '{$versionId}'", 0, $e);
        }
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
