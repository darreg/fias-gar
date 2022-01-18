<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Tag\Service\TagGeneratorInterface;
use Generator;

/**
 * @psalm-suppress MethodSignatureMismatch
 */
class TagGenerator implements TagGeneratorInterface
{
    public const BUFFER_SIZE = 10000;

    /**
     * @return Generator<string>
     * @psalm-suppress InvalidReturnStatement
     */
    public function generate(string $filePath, string $tagName): Generator
    {
        if ($tagName === '' || !file_exists($filePath)) {
            return;
        }

        $fh = fopen($filePath, 'rb');
        if ($fh === false) {
            return;
        }

        $tagBegin = '<' . $tagName . ' ';
        $tagEnd = '/>';
        $tagEndLength = \strlen($tagEnd);

        $buffer = '';
        $buffer .= fread($fh, self::BUFFER_SIZE);

        $startPosition = strpos($buffer, $tagBegin);
        if ($startPosition === false) {
            fclose($fh);
            return;
        }

        $buffer = substr($buffer, $startPosition);
        while (true) {
            $endPosition = strpos($buffer, $tagEnd);
            while ($endPosition === false) {
                $buffer .= fread($fh, self::BUFFER_SIZE);
                $endPosition = strpos($buffer, $tagEnd);
                if ($endPosition === false && feof($fh)) {
                    break 2;
                }
            }
            /** @psalm-suppress PossiblyFalseOperand */
            $endPosition += $tagEndLength;

            yield substr($buffer, 0, $endPosition);

            $buffer = substr($buffer, $endPosition);
        }

        fclose($fh);
    }
}
