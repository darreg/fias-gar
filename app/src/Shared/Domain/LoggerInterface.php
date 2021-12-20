<?php

declare(strict_types=1);

namespace App\Shared\Domain;

interface LoggerInterface
{
    public function info(string $message, array $context = []): void;

    public function warning(string $message, array $context = []): void;

    public function critical(string $message, array $context = []): void;
}
