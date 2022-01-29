<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SplitXmlFile;

use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $type;
    private string $versionId;
    private string $filePath;
    private string $fileToken;
    private string $tagName;

    /**
     * @param Version::TYPE_* $type
     */
    public function __construct(
        string $type,
        string $versionId,
        string $filePath,
        string $fileToken,
        string $tagName
    ) {
        $this->type = $type;
        $this->versionId = $versionId;
        $this->filePath = $filePath;
        $this->fileToken = $fileToken;
        $this->tagName = $tagName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
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
