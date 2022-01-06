<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\Service;

use App\DataLoad\Infrastructure\Service\TableFactory;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
final class TableFactoryTest extends KernelTestCase
{
    private TableFactory $factory;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        $this->factory = self::getContainer()->get(TableFactory::class);
    }

    public function testFactory(): void
    {
        $result = $this->factory->create('addr_obj_types');
        self::assertCount(9, $result->getColumns());
        self::assertContains('id', $result->getColumns());

        $this->expectException(LogicException::class);
        $this->factory->create('incorrect_token');
    }
}
