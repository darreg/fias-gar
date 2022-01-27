<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Repository\VersionRepositoryInterface;
use App\DataLoad\Domain\Version\Service\LoadTryIncrementorInterface;
use App\Shared\Domain\Persistence\FlusherInterface;

class LoadTryIncrementor implements LoadTryIncrementorInterface
{
    private VersionRepositoryInterface $versionRepository;
    private FlusherInterface $flusher;

    public function __construct(
        VersionRepositoryInterface $versionRepository,
        FlusherInterface $flusher
    ) {
        $this->versionRepository = $versionRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function increment(string $type, string $versionId): void
    {
        $version = $this->versionRepository->findOrFail($versionId);
        $versionType = $version->getVersionType($type);

        $versionType->incrementLoadTryNum();
        if ($versionType->getLoadTryNum() >= LoadTryIncrementorInterface::MAX_LOAD_TRY_NUM) {
            $versionType->setBrokenUrl(true);
        }

        $this->versionRepository->persist($version);
        $this->flusher->flush();
    }
}
