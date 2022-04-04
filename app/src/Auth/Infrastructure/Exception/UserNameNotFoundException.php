<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Exception;

use RuntimeException;

final class UserNameNotFoundException extends RuntimeException
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('The user <%s> does not exists', $email));
    }
}
