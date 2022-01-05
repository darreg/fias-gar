<?php

declare(strict_types=1);

namespace App\DataLoad\Domain;

interface ParserInterface
{
    /**
     * @return array<string, string>
     */
    public function parse(string $tagXml): array;
}
