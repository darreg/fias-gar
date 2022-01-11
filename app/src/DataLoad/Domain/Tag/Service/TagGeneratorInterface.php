<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Tag\Service;

use Generator;
use LogicException;
use RuntimeException;

interface TagGeneratorInterface
{
    /**
     * @throws RuntimeException
     * @throws LogicException
     * @return Generator<string>
     */
    public function generate(string $filePath, string $tagName): Generator;
}
