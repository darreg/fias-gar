<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Tag\Service;

use Generator;

interface TagGeneratorInterface
{
    /**
     * @return Generator<string>
     */
    public function generate(string $filePath, string $tagName): Generator;
}
