<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Exception\DownloadException;
use App\DataLoad\Application\Service\VersionListRefresherInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\ReadModel\VersionRow;
use App\DataLoad\Domain\Version\Repository\VersionFetcherInterface;
use App\DataLoad\Domain\Version\Repository\VersionRepositoryInterface;
use App\DataLoad\Domain\Version\Service\VersionDecoderInterface;
use App\DataLoad\Domain\Version\Service\VersionLoaderInterface;
use App\Shared\Domain\Persistence\FlusherInterface;
use LogicException;

class VersionListRefresher implements VersionListRefresherInterface
{
    private VersionLoaderInterface $versionLoader;
    private VersionDecoderInterface $versionDecoder;
    private VersionRepositoryInterface $versionRepository;
    private VersionFetcherInterface $versionFetcher;
    private FlusherInterface $flusher;

    public function __construct(
        VersionLoaderInterface $versionLoader,
        VersionDecoderInterface $versionDecoder,
        VersionRepositoryInterface $versionRepository,
        VersionFetcherInterface $versionFetcher,
        FlusherInterface $flusher
    ) {
        $this->versionLoader = $versionLoader;
        $this->versionDecoder = $versionDecoder;
        $this->versionRepository = $versionRepository;
        $this->versionFetcher = $versionFetcher;
        $this->flusher = $flusher;
    }

    public function refresh(): void
    {
        try {
            $existsVersionRows = $this->getExistsVersionRows();
            $versionsString = $this->versionLoader->load();
            $decodedVersions = $this->versionDecoder->decode($versionsString);
            foreach ($decodedVersions as $decodedVersion) {
                if (!\array_key_exists($decodedVersion->getId(), $existsVersionRows)) {
                    $this->versionRepository->persist($decodedVersion);
                    continue;
                }

                $this->updateXmlLinkStatus(
                    $existsVersionRows[$decodedVersion->getId()],
                    $decodedVersion
                );
            }
            $this->flusher->flush();
        } catch (LogicException $e) {
            throw new DownloadException('Error downloading versions', 0, $e);
        }
    }

    /**
     * @return array<string, VersionRow>
     */
    private function getExistsVersionRows(): array
    {
        $versionRows = $this->versionFetcher->findAll();
        $result = [];
        foreach ($versionRows as $versionRow) {
            $result[$versionRow->id] = $versionRow;
        }

        return $result;
    }

    private function updateXmlLinkStatus(VersionRow $versionRow, Version $decodedVersion): void
    {
        $fullNeedToUpdate = self::needToUpdateFullXmlLinkStatus($versionRow, $decodedVersion);
        $deltaNeedToUpdate = self::needToUpdateDeltaXmlLinkStatus($versionRow, $decodedVersion);

        if (!$fullNeedToUpdate && !$deltaNeedToUpdate) {
            return;
        }

        $version = $this->versionRepository->findOrFail($decodedVersion->getId());

        if ($fullNeedToUpdate) {
            $version->getFull()->setHasXml(true);
        }

        if ($deltaNeedToUpdate) {
            $version->getDelta()->setHasXml(true);
        }
    }

    private static function needToUpdateDeltaXmlLinkStatus(VersionRow $versionRow, Version $decodedVersion): bool
    {
        return !$versionRow->deltaHasXml && $decodedVersion->getDelta()->isHasXml();
    }

    private static function needToUpdateFullXmlLinkStatus(VersionRow $versionRow, Version $decodedVersion): bool
    {
        return !$versionRow->fullHasXml && $decodedVersion->getFull()->isHasXml();
    }
}
