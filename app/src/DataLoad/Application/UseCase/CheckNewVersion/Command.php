<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\CheckNewVersion;

use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    private string $id;
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
