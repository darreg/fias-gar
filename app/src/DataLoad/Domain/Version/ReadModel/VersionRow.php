<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\ReadModel;

/**
 * @psalm-suppress MissingConstructor
 */
class VersionRow
{
    public function __construct(
        public string $id
    ) {
    }
}
