<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ImportXmlFiles;

use App\Shared\Domain\Bus\Command\CommandInterface;

class Command implements CommandInterface
{
    private string $type;
    private string $versionId;
    /**
     * @var list<string>
     */
    private array $tokens;

    /**
     * @param list<string> $tokens
     */
    public function __construct(string $type, string $versionId, array $tokens)
    {
        $this->type = $type;
        $this->versionId = $versionId;
        $this->tokens = $tokens;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
    }

    /**
     * @return list<string>
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
