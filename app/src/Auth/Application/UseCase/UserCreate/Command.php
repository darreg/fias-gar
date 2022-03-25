<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\UserCreate;

use App\Shared\Domain\Bus\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements CommandInterface
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private string $email;
    /**
     * @Assert\NotBlank()
     */
    private string $firstName;
    /**
     * @Assert\NotBlank()
     */
    private string $lastName;

    public function __construct(string $email, string $firstName, string $lastName)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
