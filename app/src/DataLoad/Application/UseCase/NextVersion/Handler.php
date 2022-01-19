<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\NextVersion;

use App\DataLoad\Application\Service\VersionListRefresherInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Service\NextVersionFinderInterface;
use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;

class Handler implements QueryHandlerInterface
{
    private NextVersionFinderInterface $finder;
    private VersionListRefresherInterface $refresher;

    public function __construct(
        NextVersionFinderInterface $finder,
        VersionListRefresherInterface $refresher
    ) {
        $this->finder = $finder;
        $this->refresher = $refresher;
    }

    public function __invoke(Query $query): ResponseInterface
    {
        $this->refresher->refresh();

        /** @var Version::TYPE_* $type */
        $type = $query->getType();
        return new Response($this->finder->next($type));
    }
}
