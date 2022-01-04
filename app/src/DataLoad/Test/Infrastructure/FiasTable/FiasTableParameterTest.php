<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\FiasTable;

use App\DataLoad\Infrastructure\FiasTable\FiasTableParameter;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class FiasTableParameterTest extends KernelTestCase
{
    protected FiasTableParameter $fiasTableParameters;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $this->fiasTableParameters = self::getContainer()->get(FiasTableParameter::class);
    }

    /**
     * @dataProvider primaryKeyDataProvider
     */
    public function testPrimaryKeyByFileToken(string $token, string  $expected): void
    {
        $primaryKey = $this->fiasTableParameters->getPrimaryKeyByFileToken($token);
        self::assertEquals(
            $expected,
            $primaryKey
        );
    }

    public function testTableNameByFileToken(): void
    {
        $tableName = $this->fiasTableParameters->getTableNameByFileToken('addr_obj');
        self::assertEquals(
            'fias_gar_addrobj',
            $tableName
        );

        $this->expectException(LogicException::class);
        $this->fiasTableParameters->getTableNameByFileToken('incorrect_table_name');
    }

    public function testTagNameByFile(): void
    {
        $tagName = $this->fiasTableParameters->getTagNameByFile('addr_obj');
        self::assertEquals(
            'OBJECT',
            $tagName
        );

        $this->expectException(LogicException::class);
        $this->fiasTableParameters->getTagNameByFile('incorrect_table_name');
    }

    /**
     * @return list<array{string, string}>
     */
    private function primaryKeyDataProvider(): array
    {
        return [
            ['addr_obj', 'id'],
            ['object_levels', 'level'],
            ['reestr_objects', 'objectid'],
        ];
    }
}
