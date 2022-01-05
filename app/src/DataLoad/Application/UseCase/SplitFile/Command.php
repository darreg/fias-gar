<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SplitFile;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $filePath;
    private string $fileToken;
    private string $tagName;

    public function __construct(
        string $filePath,
        string $fileToken,
        string $tagName
    ) {
        $this->filePath = $filePath;
        $this->fileToken = $fileToken;
        $this->tagName = $tagName;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getFileToken(): string
    {
        return $this->fileToken;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }
}
