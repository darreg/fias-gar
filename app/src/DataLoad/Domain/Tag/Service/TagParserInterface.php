<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Tag\Service;

use App\DataLoad\Domain\Tag\Exception\TagAttributesNotFoundException;
use App\DataLoad\Domain\Tag\Exception\TagNotParsedException;

interface TagParserInterface
{
    /**
     * @throws TagNotParsedException
     * @throws TagAttributesNotFoundException
     * @return array<string, string>
     */
    public function parse(string $tagXml): array;
}
