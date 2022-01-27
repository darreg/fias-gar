<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\RefreshVersion;

use App\DataLoad\Application\Service\VersionListRefresherInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

final class Handler implements CommandHandlerInterface
{
    private VersionListRefresherInterface $refresher;

    public function __construct(
        VersionListRefresherInterface $refresher
    ) {
        $this->refresher = $refresher;
    }

    public function __invoke(Command $command): void
    {
        $this->refresher->refresh();
    }
}
