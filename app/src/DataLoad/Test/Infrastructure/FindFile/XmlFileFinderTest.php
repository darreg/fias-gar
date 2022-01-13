<?php

declare(strict_types=1);

namespace App\DataLoad\Test\Infrastructure\FindFile;

use App\DataLoad\Domain\Shared\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Domain\XmlFile\Entity\XmlFile;
use App\DataLoad\Infrastructure\Service\ParameterStorage;
use App\DataLoad\Infrastructure\Service\XmlFileFinder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class XmlFileFinderTest extends KernelTestCase
{
    private const TEST_DIR = '/tmp/test-xml';

    private const XML_FILE_1 = 'AS_ADDHOUSE_TYPES_20220106_2959f2f1-9f14-4325-a16a-3a25e69c4461.XML';
    private const XML_FILE_2 = 'AS_ADDR_OBJ_TYPES_20220106_94fb1ccd-bd8c-4958-b07a-fde6e9e5b05e.XML';
    private const XML_FILE_3 = 'AS_HOUSE_TYPES_20220106_f9c959ba-bf3e-4151-84b1-d4af5f84a564.XML';

    private ParameterStorage $parameterStorage;

    /**
     * @psalm-suppress  ServiceNotFound
     * @psalm-suppress  PropertyTypeCoercion
     */
    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        $container = self::getContainer();
        $this->parameterStorage = $container->get(ParameterStorage::class);

        if (!is_dir(self::TEST_DIR)) {
            mkdir(self::TEST_DIR);
        }

        touch(self::TEST_DIR . '/' . self::XML_FILE_1);
        touch(self::TEST_DIR . '/' . self::XML_FILE_2);
        touch(self::TEST_DIR . '/' . self::XML_FILE_3);
    }

    /**
     * @psalm-suppress UnusedVariable
     */
    public function testDirectoryIsNotReadableException(): void
    {
        $this->expectException(DirectoryIsNotReadableException::class);
        $xmlFileFinder = new XmlFileFinder('no-dir', $this->parameterStorage);
        $xmlFiles = $xmlFileFinder->find('addr_obj_types');
    }

    public function testFind(): void
    {
        $xmlFileFinder = new XmlFileFinder(self::TEST_DIR, $this->parameterStorage);

        $token = 'addr_obj_types';
        $xmlFiles = $xmlFileFinder->find($token);
        self::assertCount(1, $xmlFiles);
        $xmlFile = $xmlFiles[0];
        self::assertEquals(self::TEST_DIR . '/' . self::XML_FILE_2, $xmlFile->getPath());
        self::assertEquals($token, $xmlFile->getToken());
        self::assertEquals('ADDRESSOBJECTTYPE', $xmlFile->getTagName());

        $token = 'house_types';
        $xmlFiles = $xmlFileFinder->find($token);
        self::assertCount(1, $xmlFiles);
        $xmlFile = $xmlFiles[0];
        self::assertEquals(self::TEST_DIR . '/' . self::XML_FILE_3, $xmlFile->getPath());
        self::assertEquals($token, $xmlFile->getToken());
        self::assertEquals('HOUSETYPE', $xmlFile->getTagName());
    }

    public function testFindAll(): void
    {
        $xmlFileFinder = new XmlFileFinder(self::TEST_DIR, $this->parameterStorage);

        $xmlFiles = $xmlFileFinder->findAll();
        self::assertCount(3, $xmlFiles);
        self::assertContainsOnly(XmlFile::class, $xmlFiles);
    }

    public function testFindPath(): void
    {
        $xmlFileFinder = new XmlFileFinder(self::TEST_DIR, $this->parameterStorage);

        $xmlFiles = $xmlFileFinder->getAllFindPath();
        self::assertCount(3, $xmlFiles);
        self::assertContainsOnly('string', $xmlFiles);
    }
}
