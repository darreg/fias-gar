<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\Join\Request;

use App\Shared\Domain\Bus\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

/** @psalm-suppress MissingConstructor */
final class Command implements CommandInterface
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public string $email;
    /**
     * @Assert\NotBlank()
     */
    public string $firstName;

    public ?string $lastName = null;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public string $password;
}
