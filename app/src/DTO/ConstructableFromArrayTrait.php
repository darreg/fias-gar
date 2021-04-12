<?php

namespace App\DTO;

use Doctrine\Common\Collections\Collection;

trait ConstructableFromArrayTrait
{
    /**
     * @return static
     * @psalm-suppress MixedArgument
     */
    public static function fromArray(array $data)
    {
        $reflectionMethod = new \ReflectionMethod(static::class, '__construct');
        $reflectionParameters = $reflectionMethod->getParameters();
        $parameters = [];
        foreach ($reflectionParameters as $reflectionParameter) {
            $parameterName = $reflectionParameter->getName();
            if (!\array_key_exists($parameterName, $data) && !$reflectionParameter->isOptional()) {
                throw new \LogicException(
                    'Unable to instantiate \'' . static::class .
                    '\' from an array, argument ' . $parameterName . ' is missing.
                     Only the following arguments are available: ' . implode(', ', \array_keys($data))
                );
            }

            try {
                /** @psalm-suppress MixedAssignment */
                $defaultValue = $reflectionParameter->getDefaultValue();
            } catch (\ReflectionException $e) {
                $defaultValue = null;
            }

            /** @psalm-suppress MixedAssignment */
            $parameter = $data[$parameterName] ?? $defaultValue;
            if (\is_array($parameter) && $reflectionParameter->isVariadic()) {
                $parameters = \array_merge($parameters, $parameter);
                continue;
            }
            /** @psalm-suppress MixedAssignment */
            $parameters[] = $parameter;
        }

        /**
         * @psalm-suppress MixedAssignment
         * @psalm-suppress UnsafeInstantiation
         * @psalm-suppress PossiblyNullArgument
         */
        return new static(...$parameters);
    }

    /**
     * @return static
     * @throws \ReflectionException
     */
    public static function fromEntity(object $object)
    {
        $reflect = new \ReflectionClass(\get_class($object));
        $props = $reflect->getProperties();

        $data = [];
        foreach ($props as $prop) {
            if ($prop->isPrivate()) {
                $prop->setAccessible(true);
            }
            
            $value = $prop->getValue($object);
            if ($value instanceof Collection) {
                $value = $value->toArray();
            }

            $propType = $prop->getType();            
//            if ($propType !== null && !$propType->isBuiltin()) {
//                if ($value instanceof Collection) {
//                    $value = $value->toArray();
//                } else {
//                    $value = \get_object_vars($value);
//                }
//            }
            $data[$prop->getName()] = $value;
        }

        return static::fromArray($data);
    }
}
