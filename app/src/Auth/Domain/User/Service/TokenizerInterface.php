<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Service;

use App\Auth\Domain\User\Entity\Token;
use DateTimeImmutable;

interface TokenizerInterface
{
    public function generate(DateTimeImmutable $date): Token;
}
