<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\OtherCommand;

use App\DataLoad\Application\UseCase\EventForOtherCommand\Event;
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
        $this->eventBus->publish(new Event('one', 'xxx'));

        dump(['OtherCommand ' . uniqid(), $command]);
    }
}
