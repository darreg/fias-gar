<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\OtherCommand;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Command\CommandInterface;

class Handler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        dump($command);
    }
}
