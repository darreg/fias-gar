<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\RefreshVersionList;

use App\DataLoad\Application\Service\VersionListRefresherInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

class Handler implements CommandHandlerInterface
{
    private VersionListRefresherInterface $refresher;

    public function __construct(VersionListRefresherInterface $refresher)
    {
        $this->refresher = $refresher;
    }

    public function __invoke(Command $command)
    {
        $this->refresher->refresh();
    }
}
