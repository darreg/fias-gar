<?php

namespace App\Shared\Test;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * @internal
 */
trait PrivateTrait
{
    /**
     * @throws ReflectionException
     */
    public function callPrivateMethod(object $object, string $methodName, array $args = []): mixed
    {
        $reflection = new ReflectionClass(\get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        if ($method->isStatic()) {
            throw new RuntimeException("The '{$methodName}' method is static");
        }
        return $method->invokeArgs($object, $args);
    }

    /**
     * @param class-string $class
     * @throws ReflectionException
     */
    public function callPrivateStaticMethod(string $class, string $methodName, array $args = []): mixed
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        if (!$method->isStatic()) {
            throw new RuntimeException("The '{$methodName}' method is not static");
        }
        return $method->invokeArgs(null, $args);
    }
}
