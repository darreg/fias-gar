<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\RabbitMq;

use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use App\Shared\Domain\Utils;
use function Lambdish\Phunctional\last;
use function Lambdish\Phunctional\map;

final class RabbitMqQueueNameFormatter
{
    public static function format(EventSubscriberInterface $subscriber): string
    {
        $subscriberClassPaths = explode('\\', str_replace('App', 'App', \get_class($subscriber)));

        $queueNameParts = [
            $subscriberClassPaths[0],
            $subscriberClassPaths[1],
            $subscriberClassPaths[2],
            last($subscriberClassPaths),
        ];

        return implode('.', map(self::toSnakeCase(), $queueNameParts));
    }

    public static function formatRetry(EventSubscriberInterface $subscriber): string
    {
        $queueName = self::format($subscriber);

        return "retry.$queueName";
    }

    public static function formatDeadLetter(EventSubscriberInterface $subscriber): string
    {
        $queueName = self::format($subscriber);

        return "dead_letter.$queueName";
    }

    public static function shortFormat(EventSubscriberInterface $subscriber): string
    {
        $subscriberCamelCaseName = (string) last(explode('\\', \get_class($subscriber)));

        return Utils::toSnakeCase($subscriberCamelCaseName);
    }

    private static function toSnakeCase(): callable
    {
        return static fn(string $text) => Utils::toSnakeCase($text);
    }
}
