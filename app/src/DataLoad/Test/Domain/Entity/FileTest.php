<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Domain\Entity;

use App\DataLoad\Domain\Entity\File;
use App\DataLoad\Domain\Exception\VersionNotRecognizedException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FileTest extends TestCase
{
    public function testConvertVersion(): void
    {
        $result = File::convertVersion('20220107');
        self::assertEquals('2022.01.07', $result);

        $this->expectException(VersionNotRecognizedException::class);
        File::convertVersion('1234567');
    }
}
