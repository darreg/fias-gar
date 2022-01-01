<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ParseTag;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    public string $fileToken;
    private string $tagXml;

    public function __construct(
        string $fileToken,
        string $tagXml
    ) {
        $this->tagXml = $tagXml;
        $this->fileToken = $fileToken;
    }

    public function getTagXml(): string
    {
        return $this->tagXml;
    }

    public function getFileToken(): string
    {
        return $this->fileToken;
    }
}
