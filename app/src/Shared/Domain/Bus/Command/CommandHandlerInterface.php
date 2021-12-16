<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Command;

interface CommandHandlerInterface
{
    public function __invoke(CommandInterface $command): void;
}