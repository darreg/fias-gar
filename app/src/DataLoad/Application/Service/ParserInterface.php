<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

use App\DataLoad\Infrastructure\Exception\TagAttributesNotFoundException;
use App\DataLoad\Infrastructure\Exception\TagNotParsedException;

interface ParserInterface
{
    /**
     * @throws TagNotParsedException
     * @throws TagAttributesNotFoundException
     * @return array<string, string>
     */
    public function parse(string $tagXml): array;
}
