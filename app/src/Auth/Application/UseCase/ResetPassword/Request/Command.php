<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ResetPassword\Request;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
