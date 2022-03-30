<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ChangeEmail\Request;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $id;
    private string $email;

    public function __construct(string $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
