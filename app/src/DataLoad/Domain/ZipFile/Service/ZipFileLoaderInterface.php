<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\ZipFile\Service;

interface ZipFileLoaderInterface
{
    public function load(string $url, string $versionId): string;
}
