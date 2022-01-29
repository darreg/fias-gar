<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandInterface;

final class Command implements CommandInterface
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';
    public const FIELD_NAME_CHANGED_AT = 'changed_at';

    private string $type;
    private string $versionId;
    private string $fileToken;
    /**
     * @var array<string, string>
     */
    private array $values;

    /**
     * @param Version::TYPE_* $type
     * @param array<string, string> $values
     */
    public function __construct(
        string $type,
        string $versionId,
        string $fileToken,
        array $values
    ) {
        $this->type = $type;
        $this->versionId = $versionId;
        $this->fileToken = $fileToken;
        $this->values = $values;
    }

    public function getType(): string
    {
        return $this->type;
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
