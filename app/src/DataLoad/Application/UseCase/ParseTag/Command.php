<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ParseTag;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $fileToken;
    private string $tagSource;

    public function __construct(
        string $fileToken,
        string $tagSource
    ) {
        $this->tagSource = $tagSource;
        $this->fileToken = $fileToken;
    }

    public function getTagSource(): string
    {
        return $this->tagSource;
    }

    public function getFileToken(): string
    {
        return $this->fileToken;
    }
}
