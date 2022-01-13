<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\Shared;

use App\DataLoad\Infrastructure\Service\ParameterStorage;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class ParameterStorageTest extends KernelTestCase
{
    protected ParameterStorage $parameterStorage;

    /**
     * @psalm-suppress  ServiceNotFound
     * @psalm-suppress  PropertyTypeCoercion
     */
    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        $container = self::getContainer();

        $this->parameterStorage = $container->get(ParameterStorage::class);
    }

    /**
     * @dataProvider primaryKeyDataProvider
     */
    public function testPrimaryKeyByFileToken(string $token, string $expected): void
    {
        $primaryKey = $this->parameterStorage->getPrimaryKeyByFileToken($token);
        self::assertEquals(
            $expected,
            $primaryKey
        );
    }

    public function testTableNameByFileToken(): void
    {
        $tableName = $this->parameterStorage->getTableNameByFileToken('addr_obj');
        self::assertEquals(
            'fias_gar_addrobj',
            $tableName
        );

        $this->expectException(LogicException::class);
        $this->parameterStorage->getTableNameByFileToken('incorrect_table_name');
    }

    public function testTagNameByFile(): void
    {
        $tagName = $this->parameterStorage->getTagNameByFileToken('addr_obj');
        self::assertEquals(
            'OBJECT',
            $tagName
        );

        $this->expectException(LogicException::class);
        $this->parameterStorage->getTagNameByFileToken('incorrect_table_name');
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
