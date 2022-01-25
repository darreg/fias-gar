<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\Service;

use DateTimeImmutable;

interface ImportMarkerInterface
{
    public function markViewsRefreshed(DateTimeImmutable $dateTime): void;
}
