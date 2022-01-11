<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Domain\XmlFile\Entity;

use App\DataLoad\Domain\XmlFile\Entity\XmlFile;
use App\DataLoad\Domain\XmlFile\Exception\VersionNotRecognizedException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class XmlFileTest extends TestCase
{
    public function testConvertVersion(): void
    {
        $result = XmlFile::convertVersion('20220107');
        self::assertEquals('2022.01.07', $result);

        $this->expectException(VersionNotRecognizedException::class);
        XmlFile::convertVersion('1234567');
    }
}
