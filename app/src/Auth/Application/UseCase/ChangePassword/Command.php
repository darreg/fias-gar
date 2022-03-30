<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ChangePassword;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $id;
    private string $current;
    private string $new;

    public function __construct(string $id, string $current, string $new)
    {
        $this->id = $id;
        $this->current = $current;
        $this->new = $new;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCurrent(): string
    {
        return $this->current;
    }

    public function getNew(): string
    {
        return $this->new;
    }
}
