<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Exception\DownloadException;
use App\DataLoad\Application\Service\VersionDownloaderInterface;
use App\DataLoad\Domain\Version\Entity\VersionRepositoryInterface;
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
            $versionsString = $this->versionLoader->load();
            $versions = $this->versionDecoder->decode($versionsString);
            foreach ($versions as $version) {
                if ($this->versionRepository->find($version->getId()) === null) {
                    $this->versionRepository->add($version);
                }
            }
            $this->flusher->flush();
        } catch (LogicException $e) {
            throw new DownloadException('Error downloading versions', 0, $e);
        }
    }
}
