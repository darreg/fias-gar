<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Exception\InvalidVersionTypeException;
use App\DataLoad\Domain\Version\Repository\VersionFetcherInterface;
use App\DataLoad\Domain\Version\Service\NextVersionFinderInterface;
use Doctrine\DBAL\Exception as DBALException;
use RuntimeException;

class NextVersionFinder implements NextVersionFinderInterface
{
    private VersionFetcherInterface $versionFetcher;

    public function __construct(VersionFetcherInterface $versionFetcher)
    {
        $this->versionFetcher = $versionFetcher;
    }

    /**
     * @param Version::TYPE_* $type
     * @throws InvalidVersionTypeException
     */
    public function next(string $type): ?string
    {
        switch ($type) {
            case Version::TYPE_FULL:
                return $this->nextFull();
            case Version::TYPE_DELTA:
                return $this->nextDelta();
        }

        throw new InvalidVersionTypeException("Invalid version type '{$type}'");
    }

    /**
     * @throws RuntimeException
     */
    public function nextDelta(): ?string
    {
        try {
            $version = $this->versionFetcher->findOldestUncoveredDeltaVersion();
        } catch (DBALException $e) {
            throw new RuntimeException('Unable to get the next version for the database delta', 0, $e);
        }

        if ($version === null) {
            return null;
        }

        return $version->id;
    }

    /**
     * @throws RuntimeException
     */
    public function nextFull(): ?string
    {
        try {
            $version = $this->versionFetcher->findNewestUnloadedFullVersion();
        } catch (DBALException $e) {
            throw new RuntimeException('Unable to get the next version for the full database', 0, $e);
        }

        if ($version === null) {
            return null;
        }

        return $version->id;
    }
}
