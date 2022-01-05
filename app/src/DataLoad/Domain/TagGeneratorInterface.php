<?php

declare(strict_types=1);

namespace App\DataLoad\Domain;

use Generator;

interface TagGeneratorInterface
{
    public function generate(string $filePath, string $tagName): Generator;
}
