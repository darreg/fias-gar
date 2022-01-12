<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\ParseTag;

use App\DataLoad\Domain\Tag\Exception\TagAttributesNotFoundException;
use App\DataLoad\Domain\Tag\Exception\TagNotParsedException;
use App\DataLoad\Infrastructure\ParseTag\TagParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-suppress UnusedVariable
 */
final class TagParserTest extends TestCase
{
    private TagParser $tagParser;

    protected function setUp(): void
    {
        $this->tagParser = new TagParser();
    }

    public function testParseSuccess(): void
    {
        $tagXml = '<ADDRESSOBJECTTYPE ID="396" LEVEL="16" NAME="Разъезд" SHORTNAME="рзд" DESC="Разъезд" UPDATEDATE="1900-01-01" STARTDATE="1900-01-01" ENDDATE="2079-06-06" ISACTIVE="true" />';
        $result = $this->tagParser->parse($tagXml);

        self::assertCount(9, $result);
        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('isactive', $result);
    }

    public function testTagNotParsedException(): void
    {
        $tagXml = 'no-xml';
        $this->expectException(TagNotParsedException::class);
        $result = $this->tagParser->parse($tagXml);
    }

    public function testTagAttributesNotFoundException(): void
    {
        $tagXml = '<ADDRESSOBJECTTYPE />';
        $this->expectException(TagAttributesNotFoundException::class);
        $result = $this->tagParser->parse($tagXml);
    }
}
