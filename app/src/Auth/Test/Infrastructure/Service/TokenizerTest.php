<?php

declare(strict_types=1);

namespace App\Auth\Test\Infrastructure\Service;

use App\Auth\Infrastructure\Service\Tokenizer;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TokenizerTest extends TestCase
{
    public function testGenerate(): void
    {
        $interval = new DateInterval('PT1H');
        $date = new DateTimeImmutable('+1 day');

        $tokenizer = new Tokenizer($interval);

        $token = $tokenizer->generate($date);

        self::assertEquals($date->add($interval), $token->getExpires());
    }
}
