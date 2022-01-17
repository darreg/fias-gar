<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ParseTag;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $type;
    private string $versionId;
    private string $fileToken;
    private string $tagSource;

    public function __construct(
        string $type,
        string $versionId,
        string $fileToken,
        string $tagSource
    ) {
        $this->type = $type;
        $this->versionId = $versionId;
        $this->tagSource = $tagSource;
        $this->fileToken = $fileToken;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
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
