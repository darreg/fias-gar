<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\ReadModel;

use App\Shared\Infrastructure\ConstructableFromArrayTrait;
use App\Shared\Infrastructure\ConstructFromArrayInterface;

class AuthModel implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @param string[] $roles
     */
    public function __construct(
        public string $id,
        public string $email,
        public string $password_hash,
        public string $status,
        public array $roles
    ) {
    }
}
