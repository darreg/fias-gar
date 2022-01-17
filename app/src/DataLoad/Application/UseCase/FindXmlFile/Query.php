<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FindXmlFile;

use App\Shared\Domain\Bus\Query\QueryInterface;

class Query implements QueryInterface
{
    private string $token;
    private string $versionId;

    public function __construct(string $versionId, string $token)
    {
        $this->token = $token;
        $this->versionId = $versionId;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
