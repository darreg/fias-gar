<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\ReadModel;

class VersionRow
{
    public function __construct(
        public string $id,
        public bool $fullHasXml,
        public bool $deltaHasXml
    ) {
    }
}
