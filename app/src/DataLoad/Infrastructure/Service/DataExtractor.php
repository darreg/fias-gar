<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Exception\DownloadException;
use App\DataLoad\Application\Service\DataExtractorInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\XmlFile\Service\XmlFileCleanerInterface;
use App\DataLoad\Domain\ZipFile\Service\ZipFileExtractorInterface;
use App\DataLoad\Domain\ZipFile\Service\ZipFileNamerInterface;
use LogicException;

class DataExtractor implements DataExtractorInterface
{
    private ZipFileNamerInterface $zipFileService;
    private ZipFileExtractorInterface $zipFileExtractor;
    private XmlFileCleanerInterface $xmlFileCleaner;

    public function __construct(
        ZipFileNamerInterface $zipFileService,
        ZipFileExtractorInterface $zipFileExtractor,
        XmlFileCleanerInterface $xmlFileCleaner,
    ) {
        $this->zipFileService = $zipFileService;
        $this->zipFileExtractor = $zipFileExtractor;
        $this->xmlFileCleaner = $xmlFileCleaner;
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function extract(string $type, string $versionId): void
    {
        try {
            $this->xmlFileCleaner->clean();
            $fileName = $this->zipFileService->getFileName($type, $versionId);
            $this->zipFileExtractor->extract($versionId, $fileName);
        } catch (LogicException $e) {
            throw new DownloadException("Error extracting the {$type} database version '{$versionId}'", 0, $e);
        }
    }
}
