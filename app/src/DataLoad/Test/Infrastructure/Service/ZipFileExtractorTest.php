<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\Service;

use App\DataLoad\Infrastructure\Service\ZipFileExtractor;
use App\Shared\Test\PrivateTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ZipFileExtractorTest extends TestCase
{
    use PrivateTrait;

    public function testExcludes(): void
    {
        /** @var string $result */
        $result = $this->callPrivateStaticMethod(ZipFileExtractor::class, 'excludes', [
            [
                'addr_obj' => '*_ADDR_OBJ_*',
                'addr_obj_division' => '*_ADDR_OBJ_DIVISION_*',
                'addr_obj_params' => '*_PARAMS_*',
                'carplaces_params' => '*_PARAMS_*',
            ],
            [
                'object_levels',
                'addr_obj_params',
                'addr_obj',
            ],
        ]);
        self::assertEquals(' -x *_ADDR_OBJ_* -x *_PARAMS_*', $result);

        /** @var string $result */
        $result = $this->callPrivateStaticMethod(ZipFileExtractor::class, 'excludes', [
            [
                'addr_obj_params' => '*_PARAMS_*',
                'carplaces_params' => '*_PARAMS_*',
            ],
            [
                'addr_obj_params',
                'carplaces_params',
            ],
        ]);
        self::assertEquals(' -x *_PARAMS_*', $result);
    }
}
