<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\FiasTable;

use App\DataLoad\Infrastructure\FiasTable\FiasTableFactory;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
final class FiasTableFactoryTest extends KernelTestCase
{
    private FiasTableFactory $fiasTableFactory;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        $this->fiasTableFactory = self::getContainer()->get(FiasTableFactory::class);
    }

    public function testFactory(): void
    {
        $result = $this->fiasTableFactory->create('addr_obj_types');
        self::assertCount(9, $result->getColumns());
        self::assertContains('id', $result->getColumns());

        $this->expectException(LogicException::class);
        $this->fiasTableFactory->create('incorrect_token');
    }
}
