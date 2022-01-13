<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\Import;

use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    /**
     * @var list<string>
     */
    private array $tokens;

    /**
     * @param list<string> $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @return list<string>
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
