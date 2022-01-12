<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\SplitFile;

use App\DataLoad\Infrastructure\SplitFile\TagGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TagGeneratorTest extends TestCase
{
    private const TEST_DIR = '/tmp/test-xml';
    private const TEST_FILE = self::TEST_DIR . '/tag_generator_test.xml';

    private TagGenerator $tagGenerator;

    protected function setUp(): void
    {
        $this->tagGenerator = new TagGenerator();

        if (!is_dir(self::TEST_DIR)) {
            mkdir(self::TEST_DIR);
        }

        file_put_contents(
            self::TEST_FILE,
            '<ADDRESSOBJECTTYPE ID="1" /><ADDRESSOBJECTTYPE ID="2" /><ADDRESSOBJECTTYPE ID="3" />'
        );
    }

    public function testGeneratorSuccess(): void
    {
        $result = iterator_to_array(
            $this->tagGenerator->generate(self::TEST_FILE, 'ADDRESSOBJECTTYPE')
        );

        self::assertCount(3, $result);
    }

    public function testGeneratorFail(): void
    {
        $result = iterator_to_array(
            $this->tagGenerator->generate('no-file', 'no-tag')
        );

        self::assertCount(0, $result);
    }
}
