<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure;

use App\DataLoad\Domain\ParserInterface;
use DomainException;
use function Lambdish\Phunctional\reindex;

class Parser implements ParserInterface
{
    /**
     * @return array<string, string>
     */
    public function parse(string $tagXml): array
    {
        $xmlElement = simplexml_load_string($tagXml);
        if (empty($xmlElement)) {
            throw new DomainException('Tag could not be parsed');
        }

        $xmlData = (array)$xmlElement;
        if (empty($xmlData['@attributes'])) {
            throw new DomainException('Failed to get tag attribute values');
        }

        $xmlAttributes = (array)$xmlData['@attributes'];

        /**
         * @var array<string, string> $result
         * @psalm-suppress UnusedClosureParam
         */
        $result = reindex(static fn (string $value, string $key) => strtolower($key), $xmlAttributes);

        return $result;
    }
}
