<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\CheckNewVersion;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

class Handler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        echo $command->name();
    }
}