<?php

declare(strict_types=1);

namespace App\Tests\Auth\Infrastructure\Service;

use App\Auth\Infrastructure\Service\PasswordHasher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class PasswordHasherTest extends KernelTestCase
{
    private PasswordHasher $passwordHasher;

    /**
     * @psalm-suppress  ServiceNotFound
     * @psalm-suppress  PropertyTypeCoercion
     */
    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        $container = self::getContainer();

        $this->passwordHasher = $container->get(PasswordHasher::class);
    }

    public function testHash(): void
    {
        $password = 'new-password';
        $hash = $this->passwordHasher->hashPassword($password);

        self::assertNotEmpty($hash);
        self::assertNotEquals($password, $hash);
    }

    public function testValidate(): void
    {
        $password = 'new-password';
        $hash = $this->passwordHasher->hashPassword($password);

        self::assertTrue($this->passwordHasher->isPasswordValid($hash, $password));
        self::assertFalse($this->passwordHasher->isPasswordValid($hash, 'wrong-password'));
    }
}
