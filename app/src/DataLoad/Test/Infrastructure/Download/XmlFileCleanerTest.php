<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\Download;

use App\DataLoad\Domain\Shared\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Domain\XmlFile\Exception\CleanUpException;
use App\DataLoad\Infrastructure\Service\XmlFileCleaner;
use App\DataLoad\Infrastructure\Service\XmlFileFinder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
final class XmlFileCleanerTest extends KernelTestCase
{
    private const TEST_DIR = '/tmp/test-xml';

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        if (!is_dir(self::TEST_DIR)) {
            mkdir(self::TEST_DIR);
        }
    }

    public function testCleanSuccess(): void
    {
        $xmlFileFinder = $this->createStub(XmlFileFinder::class);
        $xmlFileFinder->method('getAllFindPath')
            ->willReturnCallback(static function () {
                return self::getFiles();
            });

        $xmlFileCleaner = new XmlFileCleaner(self::TEST_DIR, $xmlFileFinder);

        touch(self::TEST_DIR . '/one.xml');
        touch(self::TEST_DIR . '/two.xml');
        touch(self::TEST_DIR . '/three.xml');

        self::assertEquals(3, self::getFilesNum());

        $xmlFileCleaner->clean();

        self::assertEquals(0, self::getFilesNum());
    }

    public function testCleanUpException(): void
    {
        $xmlFileFinder = $this->createStub(XmlFileFinder::class);
        $xmlFileFinder->method('getAllFindPath')
            ->willReturn(['one.xml']);

        $xmlFileCleaner = new XmlFileCleaner(self::TEST_DIR, $xmlFileFinder);

        $this->expectException(CleanUpException::class);
        $xmlFileCleaner->clean();
    }

    public function testDirectoryIsNotReadableException(): void
    {
        $xmlFileFinder = $this->createStub(XmlFileFinder::class);
        $xmlFileCleaner = new XmlFileCleaner('no-dir', $xmlFileFinder);

        $this->expectException(DirectoryIsNotReadableException::class);
        $xmlFileCleaner->clean();
    }

    private static function getFilesNum(): int
    {
        return \count(self::getFiles());
    }

    private static function getFiles(): array
    {
        $result = glob(self::TEST_DIR . '/*.xml');
        return $result === false ? [] : $result;
    }
}
