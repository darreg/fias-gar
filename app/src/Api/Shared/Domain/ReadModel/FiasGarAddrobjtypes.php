<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ReadModel;

use DateTimeImmutable;

final class FiasGarAddrobjtypes
{
    public int $id;
    public int $level;
    public string $shortname;
    public string $name;
    public string $desc;
    public DateTimeImmutable $startdate;
    public DateTimeImmutable $enddate;
    public DateTimeImmutable $updatedate;
    public bool $isactive;
}
