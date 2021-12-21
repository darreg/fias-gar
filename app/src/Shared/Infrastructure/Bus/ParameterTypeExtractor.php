<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use LogicException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reindex;

final class ParameterTypeExtractor
{
    public static function fromHandlers(iterable $handlers): array
    {
        return map(
            self::turnIntoArray(),
            reindex(
                self::newKeyFromParameterType(),
                $handlers
            )
        );
    }

    private static function turnIntoArray(): callable
    {
        return static fn($value) => [$value];
    }

    private static function newKeyFromParameterType(): callable
    {
        return static fn(callable $handler): ?string => self::extractParameterType($handler);
    }

    /**
     * @throws ReflectionException
     * @throws LogicException
     */
    private static function extractParameterType($class): ?string
    {
        $reflector = new ReflectionClass($class);
        $method = $reflector->getMethod('__invoke');

        return self::getMethodParameterType($method);
    }

    /**
     * @throws LogicException
     */
    private static function getMethodParameterType(ReflectionMethod $method): ?string
    {
        if ($method->getNumberOfParameters() !== 1) {
            return null;
        }

        /** @var ReflectionNamedType $fistParameterType */
        $fistParameterType = $method->getParameters()[0]->getType();

        if ($fistParameterType === null) {
            throw new LogicException('Missing type hint for the first parameter of __invoke');
        }

        return $fistParameterType->getName();
    }
}
