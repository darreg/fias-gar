<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\CheckNewVersion;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Command\CommandInterface;

class Handler implements CommandHandlerInterface
{
    public function __invoke(CommandInterface $command): void
    {
        dump($command->name());
    }
}