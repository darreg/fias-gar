<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\GetNextVersion;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Service\NextVersionFinderInterface;
use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;

class Handler implements QueryHandlerInterface
{
    private NextVersionFinderInterface $finder;

    public function __construct(NextVersionFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(Query $query): ?ResponseInterface
    {
        /** @var Version::TYPE_* $type */
        $type = $query->getType();
        return new Response($this->finder->next($type));
    }
}
