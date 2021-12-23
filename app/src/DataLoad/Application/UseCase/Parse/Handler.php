<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Parse;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

class Handler implements CommandHandlerInterface
{
    public function __invoke(Command $command): void
    {
        dump(['Parse ' . uniqid(), $command]);
    }
}
