<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    public string $fileToken;
    public array $values;

    public function __construct(string $fileToken, array $values)
    {
        $this->fileToken = $fileToken;
        $this->values = $values;
    }

    public function getFileToken(): string
    {
        return $this->fileToken;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
