<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\CheckNewVersion;

use App\DataLoad\Application\UseCase\EventForOtherCommand\Event2;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Infrastructure\Bus\Event\EventBus;

class Handler implements CommandHandlerInterface
{
    private EventBus $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function __invoke(Command $command): void
    {
        $this->eventBus->publish(new Event2('two', 'zzz'));

        dump(['CheckNewVersion ' . uniqid(), $command]);
    }
}
