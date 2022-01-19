<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Exception\DownloadException;
use App\DataLoad\Application\Service\VersionListRefresherInterface;
use App\DataLoad\Domain\Version\Entity\Version;
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
    private FlusherInterface $flusher;

    public function __construct(
        VersionLoaderInterface $versionLoader,
        VersionDecoderInterface $versionDecoder,
        VersionRepositoryInterface $versionRepository,
        FlusherInterface $flusher
    ) {
        $this->versionLoader = $versionLoader;
        $this->versionDecoder = $versionDecoder;
        $this->versionRepository = $versionRepository;
        $this->flusher = $flusher;
    }

    public function refresh(): void
    {
        try {
            $existsVersions = $this->getExistsVersions();
            $versionsString = $this->versionLoader->load();
            $versions = $this->versionDecoder->decode($versionsString);
            foreach ($versions as $version) {
                if (!\array_key_exists($version->getId(), $existsVersions)) {
                    $this->versionRepository->persist($version);
                }
            }
            $this->flusher->flush();
        } catch (LogicException $e) {
            throw new DownloadException('Error downloading versions', 0, $e);
        }
    }

    /**
     * @return array<string, Version>
     */
    private function getExistsVersions(): array
    {
        $versions = $this->versionRepository->findAll();
        $result = [];
        foreach ($versions as $version) {
            $result[$version->getId()] = $version;
        }

        return $result;
    }
}
