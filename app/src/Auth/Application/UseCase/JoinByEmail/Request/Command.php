<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\JoinByEmail\Request;

use App\Shared\Domain\Bus\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Command implements CommandInterface
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
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    private string $password;

    public function __construct(string $email, string $firstName, string $lastName, string $password)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
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

    public function getPassword(): string
    {
        return $this->password;
    }
}
