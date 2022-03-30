<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use App\Auth\Domain\User\Entity\Token;
use App\Auth\Domain\User\Service\TokenizerInterface;
use App\Shared\Domain\Internal\Uuid;
use DateInterval;
use DateTimeImmutable;

final class Tokenizer implements TokenizerInterface
{
    private DateInterval $interval;

    public function __construct(DateInterval $interval)
    {
        $this->interval = $interval;
    }

    public function generate(DateTimeImmutable $date): Token
    {
        return new Token(
            Uuid::generate(),
            $date->add($this->interval)
        );
    }
}
