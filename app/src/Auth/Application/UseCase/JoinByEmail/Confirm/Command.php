<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\JoinByEmail\Confirm;

use App\Shared\Domain\Bus\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Command implements CommandInterface
{
    /**
     * @Assert\NotBlank()
     */
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
