<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure;

use App\DataLoad\Domain\TagGeneratorInterface;
use Generator;
use LogicException;

class TagGenerator implements TagGeneratorInterface
{
    public const BUFFER_SIZE = 10000;

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
            throw new LogicException("Could not open the file '{$filePath}'");
        }

        $tagBegin = '<' . $tagName . ' ';
        $tagEnd = '/>';
        $tagEndLength = \strlen($tagEnd);

        $buffer = '';
        $buffer .= fread($fh, self::BUFFER_SIZE);

        $startPosition = strpos($buffer, $tagBegin);
        if ($startPosition === false) {
            fclose($fh);
            return null;
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
