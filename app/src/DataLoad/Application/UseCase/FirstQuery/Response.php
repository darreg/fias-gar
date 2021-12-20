<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FirstQuery;

use App\Shared\Domain\Bus\Query\ResponseInterface;

class Response implements ResponseInterface
{
    public int $id;
    public string $roles;
    public string $email;
    public bool $status;

    public function __construct(int $id, string $roles, string $email, bool $status)
    {
        $this->id = $id;
        $this->roles = $roles;
        $this->email = $email;
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['roles'],
            $data['email'],
            $data['status']
        );
    }
}
