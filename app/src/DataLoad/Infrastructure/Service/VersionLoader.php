<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Version\Service\VersionLoaderInterface;
use RuntimeException;

class VersionLoader implements VersionLoaderInterface
{
    private string $versionListUrl;

    public function __construct(string $versionListUrl)
    {
        $this->versionListUrl = $versionListUrl;
    }

    /**
     * @throws RuntimeException
     */
    public function load(): string
    {
        $result = file_get_contents($this->versionListUrl);
        if (!$result) {
            throw new RuntimeException('Versions data download error');
        }

        return $result;
    }
}
