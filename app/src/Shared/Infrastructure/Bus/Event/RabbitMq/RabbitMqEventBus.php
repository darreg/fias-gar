<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPException;
use AMQPExchangeException;
use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Bus\Event\EventBusInterface;
use App\Shared\Infrastructure\Bus\Event\DomainEventJsonSerializer;
use App\Shared\Infrastructure\Bus\Event\MySql\MySqlDoctrineEventBus;
use JsonException;
use function Lambdish\Phunctional\each;

final class RabbitMqEventBus implements EventBusInterface
{
    private RabbitMqConnection    $connection;
    private string                $exchangeName;
    private MySqlDoctrineEventBus $failoverPublisher;

    public function __construct(
        RabbitMqConnection $connection,
        string $exchangeName,
        MySqlDoctrineEventBus $failoverPublisher
    ) {
        $this->connection        = $connection;
        $this->exchangeName      = $exchangeName;
        $this->failoverPublisher = $failoverPublisher;
    }

    public function publish(EventInterface ...$events): void
    {
        each($this->publisher(), $events);
    }

    private function publisher(): callable
    {
        return function (EventInterface $event) {
            try {
                $this->publishEvent($event);
            } catch (AMQPException $e) {
                $this->failoverPublisher->publish($event);
            }
        };
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws JsonException
     */
    private function publishEvent(EventInterface $event): void
    {
        $body       = DomainEventJsonSerializer::serialize($event);
        $routingKey = $event::eventName();
        $messageId  = $event->eventId();

        $this->connection->exchange($this->exchangeName)->publish(
            $body,
            $routingKey,
            AMQP_NOPARAM,
            [
                'message_id'       => $messageId,
                'content_type'     => 'application/json',
                'content_encoding' => 'utf-8',
            ]
        );
    }
}
