<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\ZipFile\Service;

use App\DataLoad\Domain\Shared\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Domain\XmlFile\Exception\CleanUpException;
use App\DataLoad\Domain\ZipFile\Exception\NoFilesAfterUnpackingException;
use App\DataLoad\Domain\ZipFile\Exception\ZipFileNotFoundException;
use RuntimeException;

interface ZipFileExtractorInterface
{
    /**
     * @throws DirectoryIsNotReadableException
     * @throws CleanUpException
     * @throws ZipFileNotFoundException
     * @throws NoFilesAfterUnpackingException
     * @throws RuntimeException
     */
    public function extract(string $versionId, string $fileName): void;
}
