<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure;

use App\DataLoad\Domain\ParserInterface;
use App\DataLoad\Infrastructure\Exception\TagAttributesNotFoundException;
use App\DataLoad\Infrastructure\Exception\TagNotParsedException;
use LibXMLError;
use function Lambdish\Phunctional\reindex;

class Parser implements ParserInterface
{
    /**
     * @throws TagNotParsedException
     * @throws TagAttributesNotFoundException
     * @return array<string, string>
     */
    public function parse(string $tagXml): array
    {
        libxml_use_internal_errors(true);
        $xmlElement = simplexml_load_string($tagXml);
        $xmlErrors = array_map(static fn (libXMLError $error) => $error->message, libxml_get_errors());

        if ($xmlElement === false) {
            throw new TagNotParsedException(
                sprintf('Tag could not be parsed (%s)', implode('; ', $xmlErrors))
            );
        }

        $xmlData = (array)$xmlElement;
        if (empty($xmlData['@attributes'])) {
            throw new TagAttributesNotFoundException("Failed to get tag attribute values '{$tagXml}'");
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
