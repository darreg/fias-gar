<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Exception\InvalidVersionTypeException;
use App\DataLoad\Domain\ZipFile\Service\ZipFileNamerInterface;

class ZipFileNamer implements ZipFileNamerInterface
{
    private string $fullFileName;
    private string $deltaFileName;

    public function __construct(
        string $fullFileName,
        string $deltaFileName
    ) {
        $this->fullFileName = $fullFileName;
        $this->deltaFileName = $deltaFileName;
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function getFileName(string $type, string $versionId): string
    {
        return match ($type) {
            Version::TYPE_FULL => $versionId . '_' . $this->fullFileName,
            Version::TYPE_DELTA => $versionId . '_' . $this->deltaFileName,
            default => throw new InvalidVersionTypeException("Invalid version type '{$type}'"),
        };
    }
}
