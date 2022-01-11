<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Tag;

use App\DataLoad\Domain\Tag\Service\TagGeneratorInterface;
use Generator;
use LogicException;
use RuntimeException;

/**
 * @psalm-suppress MethodSignatureMismatch
 */
class TagGenerator implements TagGeneratorInterface
{
    public const BUFFER_SIZE = 10000;

    /**
     * @throws RuntimeException
     * @throws LogicException
     * @return Generator<string>
     */
    public function generate(string $filePath, string $tagName): Generator
    {
        if ($filePath === '') {
            throw new LogicException('The file path is not specified');
        }

        if ($tagName === '') {
            throw new LogicException('Tag name not specified');
        }

        $fh = fopen($filePath, 'rb');
        if ($fh === false) {
            throw new RuntimeException("Could not open the file '{$filePath}'");
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
