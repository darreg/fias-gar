<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\CommandInterface;
use RuntimeException;

class CommandNotRegisteredError extends RuntimeException
{
    public function __construct(CommandInterface $command)
    {
        $commandClass = \get_class($command);

        parent::__construct("The command <$commandClass> hasn't a command handler associated");
    }
}