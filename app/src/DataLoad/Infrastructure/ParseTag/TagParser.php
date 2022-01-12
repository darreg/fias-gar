<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\ParseTag;

use App\DataLoad\Domain\Tag\Exception\TagAttributesNotFoundException;
use App\DataLoad\Domain\Tag\Exception\TagNotParsedException;
use App\DataLoad\Domain\Tag\Service\TagParserInterface;
use LibXMLError;
use function Lambdish\Phunctional\reindex;

class TagParser implements TagParserInterface
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
