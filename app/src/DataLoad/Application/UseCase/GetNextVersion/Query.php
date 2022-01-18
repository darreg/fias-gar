<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\GetNextVersion;

use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Query\QueryInterface;

class Query implements QueryInterface
{
    private string $type;

    /**
     * @param Version::TYPE_* $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
