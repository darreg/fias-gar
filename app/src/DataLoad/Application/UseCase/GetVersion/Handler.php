<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\GetVersion;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Exception\IncorrectVersionException;
use App\DataLoad\Domain\Version\Repository\VersionRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use Psr\Log\LoggerInterface;

class Handler implements QueryHandlerInterface
{
    private VersionRepositoryInterface $versionRepository;
    private LoggerInterface $logger;

    public function __construct(
        VersionRepositoryInterface $versionRepository,
        LoggerInterface $logger
    ) {
        $this->versionRepository = $versionRepository;
        $this->logger = $logger;
    }

    /**
     * @throws IncorrectVersionException
     */
    public function __invoke(Query $query): ResponseInterface
    {
        try {
            $version = $this->getRelevantVersion($query);
        } catch (IncorrectVersionException|EntityNotFoundException $e) {
            $this->logger->critical($e->getMessage());
            return new Response(null);
        }

        return new Response($version);
    }

    private function getRelevantVersion(Query $query): Version
    {
        $type = $query->getType();
        $versionId = $query->getVersionId();

        $version = $this->versionRepository->findOrFail($versionId);

        if ($version->isCovered()) {
            throw new IncorrectVersionException("Version '{$versionId}' already covered by a newer version");
        }

        /** @psalm-suppress ArgumentTypeCoercion */
        $versionType = $version->getVersionType($type);

        if (!$versionType->isHasXml()) {
            throw new IncorrectVersionException("Version '{$versionId}' does not have a {$type} url");
        }

        if ($versionType->getLoadedAt() !== null) {
            throw new IncorrectVersionException("Version '{$versionId}' has already been loaded");
        }

        if ($versionType->isBrokenUrl()) {
            throw new IncorrectVersionException("Version '{$versionId}' has a broken {$type} url");
        }

        return $version;
    }
}
