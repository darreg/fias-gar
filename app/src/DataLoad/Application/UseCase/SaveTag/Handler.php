<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\DataLoad\Domain\Repository\FiasTableSaverInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;

class Handler implements CommandHandlerInterface
{
    private CommandBus $commandBus;
    private FiasTableSaverInterface $fiasTableSaver;

    public function __construct(
        CommandBus $commandBus,
        FiasTableSaverInterface $fiasTableSaver
    )
    {
        $this->commandBus = $commandBus;
        $this->fiasTableSaver = $fiasTableSaver;
    }

    public function __invoke(Command $command): void
    {
        $this->fiasTableSaver->upsert($command->getFileToken(), $command->getValues());
    }
}
