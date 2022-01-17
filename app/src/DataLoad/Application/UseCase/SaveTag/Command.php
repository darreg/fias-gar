<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    private string $versionId;
    private string $fileToken;
    /**
     * @var array<string, string>
     */
    private array $values;

    /**
     * @param array<string, string> $values
     */
    public function __construct(
        string $versionId,
        string $fileToken,
        array $values
    ) {
        $this->versionId = $versionId;
        $this->fileToken = $fileToken;
        $this->values = $values;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
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
