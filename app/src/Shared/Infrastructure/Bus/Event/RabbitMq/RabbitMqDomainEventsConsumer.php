<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPEnvelope;
use AMQPExchangeException;
use AMQPQueue;
use AMQPQueueException;
use App\Shared\Infrastructure\Bus\Event\DomainEventJsonDeserializer;
use Throwable;
use function Lambdish\Phunctional\assoc;
use function Lambdish\Phunctional\get;

final class RabbitMqDomainEventsConsumer
{
    private RabbitMqConnection          $connection;
    private DomainEventJsonDeserializer $deserializer;
    private string                      $exchangeName;
    private int                         $maxRetries;

    public function __construct(
        RabbitMqConnection $connection,
        DomainEventJsonDeserializer $deserializer,
        string $exchangeName,
        int $maxRetries
    ) {
        $this->connection   = $connection;
        $this->deserializer = $deserializer;
        $this->exchangeName = $exchangeName;
        $this->maxRetries   = $maxRetries;
    }

    public function consume(callable $subscriber, string $queueName): void
    {
        try {
            $this->connection->queue($queueName)->consume($this->consumer($subscriber));
        } catch (AMQPQueueException $e) {
            // We don't want to raise an error if there are no messages in the queue
        }
    }

    private function consumer(callable $subscriber): callable
    {
        return function (AMQPEnvelope $envelope, AMQPQueue $queue) use ($subscriber) {
            $event = $this->deserializer->deserialize($envelope->getBody());

            try {
                $subscriber($event);
            } catch (Throwable $error) {
                $this->handleConsumptionError($envelope, $queue);

                throw $error;
            }

            $queue->ack($envelope->getDeliveryTag());
        };
    }

    /**
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     */
    private function handleConsumptionError(AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $this->hasBeenRedeliveredTooMuch($envelope)
            ? $this->sendToDeadLetter($envelope, $queue)
            : $this->sendToRetry($envelope, $queue);

        $queue->ack($envelope->getDeliveryTag());
    }

    private function hasBeenRedeliveredTooMuch(AMQPEnvelope $envelope): bool
    {
        return get('redelivery_count', $envelope->getHeaders(), 0) >= $this->maxRetries;
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    private function sendToDeadLetter(AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $this->sendMessageTo(RabbitMqExchangeNameFormatter::deadLetter($this->exchangeName), $envelope, $queue);
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    private function sendToRetry(AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $this->sendMessageTo(RabbitMqExchangeNameFormatter::retry($this->exchangeName), $envelope, $queue);
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    private function sendMessageTo(string $exchangeName, AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $headers = $envelope->getHeaders();

        $this->connection->exchange($exchangeName)->publish(
            $envelope->getBody(),
            $queue->getName(),
            AMQP_NOPARAM,
            [
                'message_id'       => $envelope->getMessageId(),
                'content_type'     => $envelope->getContentType(),
                'content_encoding' => $envelope->getContentEncoding(),
                'priority'         => $envelope->getPriority(),
                'headers'          => assoc($headers, 'redelivery_count', get('redelivery_count', $headers, 0) + 1),
            ]
        );
    }
}
