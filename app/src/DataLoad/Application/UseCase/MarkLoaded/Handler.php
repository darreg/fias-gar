<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\MarkLoaded;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Exception\InvalidVersionTypeException;
use App\DataLoad\Domain\Version\Repository\VersionRepositoryInterface;
use App\DataLoad\Domain\Version\Service\VersionCovererInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Persistence\FlusherInterface;
use DateTimeImmutable;

class Handler implements CommandHandlerInterface
{
    private VersionRepositoryInterface $versionRepository;
    private VersionCovererInterface $deltaVersionCoverer;
    private FlusherInterface $flusher;

    public function __construct(
        VersionRepositoryInterface $versionRepository,
        VersionCovererInterface $deltaVersionCoverer,
        FlusherInterface $flusher
    ) {
        $this->versionRepository = $versionRepository;
        $this->deltaVersionCoverer = $deltaVersionCoverer;
        $this->flusher = $flusher;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(Command $command)
    {
        $type = $command->getType();
        $versionId = $command->getVersionId();

        $version = $this->versionRepository->findOrFail($versionId);

        switch ($type) {
            case Version::TYPE_DELTA:
                $version->getDelta()->setLoadedAt(new DateTimeImmutable());
                break;
            case Version::TYPE_FULL:
                $version->getFull()->setLoadedAt(new DateTimeImmutable());
                $this->deltaVersionCoverer->cover($versionId);
                break;
            default:
                throw new InvalidVersionTypeException("Invalid version type '{$type}'");
        }

        $this->flusher->flush();
    }
}
