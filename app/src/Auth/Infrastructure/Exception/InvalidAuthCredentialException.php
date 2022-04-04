<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Exception;

use App\Auth\Domain\User\Entity\Email;
use RuntimeException;

final class InvalidAuthCredentialException extends RuntimeException
{
    public function __construct(Email $email)
    {
        parent::__construct(sprintf('The credentials for <%s> are invalid', $email->getValue()));
    }
}
