<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Exception\DownloadException;
use App\DataLoad\Application\Service\VersionDownloaderInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Repository\VersionRepositoryInterface;
use App\DataLoad\Domain\Version\Service\VersionDecoderInterface;
use App\DataLoad\Domain\Version\Service\VersionLoaderInterface;
use App\Shared\Infrastructure\Persistence\DoctrineFlusher;
use LogicException;

class VersionDownloader implements VersionDownloaderInterface
{
    private VersionLoaderInterface $versionLoader;
    private VersionDecoderInterface $versionDecoder;
    private VersionRepositoryInterface $versionRepository;
    private DoctrineFlusher $flusher;

    public function __construct(
        VersionLoaderInterface $versionLoader,
        VersionDecoderInterface $versionDecoder,
        VersionRepositoryInterface $versionRepository,
        DoctrineFlusher $flusher
    ) {
        $this->versionLoader = $versionLoader;
        $this->versionDecoder = $versionDecoder;
        $this->versionRepository = $versionRepository;
        $this->flusher = $flusher;
    }

    public function download(): void
    {
        try {
            $existsVersions = $this->getExistsVersions();
            $versionsString = $this->versionLoader->load();
            $versions = $this->versionDecoder->decode($versionsString);
            foreach ($versions as $version) {
                if (!\array_key_exists($version->getId(), $existsVersions)) {
                    $this->versionRepository->add($version);
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
