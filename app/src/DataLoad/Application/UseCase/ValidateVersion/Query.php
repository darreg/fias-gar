<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ValidateVersion;

use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Query\QueryInterface;

class Query implements QueryInterface
{
    private string $type;
    private string $versionId;

    /**
     * @param Version::TYPE_* $type
     */
    public function __construct(string $type, string $versionId)
    {
        $this->type = $type;
        $this->versionId = $versionId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
    }
}
