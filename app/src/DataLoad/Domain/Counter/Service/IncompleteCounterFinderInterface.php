<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Counter\Service;

use App\DataLoad\Domain\Counter\Entity\Counter;

interface IncompleteCounterFinderInterface
{
    public const EXPIRE_INTERVAL = 3600 * 24;

    /**
     * @return list<Counter>
     */
    public function find(): array;

    public function check(): bool;
}
