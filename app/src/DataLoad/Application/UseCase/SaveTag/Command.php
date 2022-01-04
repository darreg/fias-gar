<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    public string $fileToken;
    /**
     * @var array<string, string> $values
     */
    public array $values;

    /**
     * @param array<string, string> $values
     */    
    public function __construct(string $fileToken, array $values)
    {
        $this->fileToken = $fileToken;
        $this->values = $values;
    }

    public function getFileToken(): string
    {
        return $this->fileToken;
    }

    /**
     * @return array<string, string>
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
