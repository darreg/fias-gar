<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ChangeEmail\Confirm;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
