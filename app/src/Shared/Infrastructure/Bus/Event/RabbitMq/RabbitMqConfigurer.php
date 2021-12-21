<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPExchangeException;
use AMQPQueue;
use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use function Lambdish\Phunctional\each;

final class RabbitMqConfigurer
{
    private RabbitMqConnection $connection;

    public function __construct(RabbitMqConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPConnectionException
     * @throws AMQPChannelException
     */
    public function configure(string $exchangeName, EventSubscriberInterface ...$subscribers): void
    {
        $retryExchangeName      = RabbitMqExchangeNameFormatter::retry($exchangeName);
        $deadLetterExchangeName = RabbitMqExchangeNameFormatter::deadLetter($exchangeName);

        $this->declareExchange($exchangeName);
        $this->declareExchange($retryExchangeName);
        $this->declareExchange($deadLetterExchangeName);

        $this->declareQueues($exchangeName, $retryExchangeName, $deadLetterExchangeName, ...$subscribers);
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    private function declareExchange(string $exchangeName): void
    {
        $exchange = $this->connection->exchange($exchangeName);
        $exchange->setType(AMQP_EX_TYPE_TOPIC);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();
    }

    private function declareQueues(
        string $exchangeName,
        string $retryExchangeName,
        string $deadLetterExchangeName,
        EventSubscriberInterface ...$subscribers
    ): void {
        each($this->queueDeclarator($exchangeName, $retryExchangeName, $deadLetterExchangeName), $subscribers);
    }

    private function queueDeclarator(
        string $exchangeName,
        string $retryExchangeName,
        string $deadLetterExchangeName
    ): callable {
        return function (EventSubscriberInterface $subscriber) use (
            $exchangeName,
            $retryExchangeName,
            $deadLetterExchangeName
        ) {
            $queueName           = RabbitMqQueueNameFormatter::format($subscriber);
            $retryQueueName      = RabbitMqQueueNameFormatter::formatRetry($subscriber);
            $deadLetterQueueName = RabbitMqQueueNameFormatter::formatDeadLetter($subscriber);

            $queue           = $this->declareQueue($queueName);
            $retryQueue      = $this->declareQueue($retryQueueName, $exchangeName, $queueName, 1000);
            $deadLetterQueue = $this->declareQueue($deadLetterQueueName);

            $queue->bind($exchangeName, $queueName);
            $retryQueue->bind($retryExchangeName, $queueName);
            $deadLetterQueue->bind($deadLetterExchangeName, $queueName);

            foreach ($subscriber->subscribedTo() as $eventClass) {
                $queue->bind($exchangeName, $eventClass::eventName());
            }
        };
    }

    /**
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    private function declareQueue(
        string $name,
        string $deadLetterExchange = null,
        string $deadLetterRoutingKey = null,
        int $messageTtl = null
    ): AMQPQueue {
        $queue = $this->connection->queue($name);

        if ($deadLetterExchange !== null) {
            $queue->setArgument('x-dead-letter-exchange', $deadLetterExchange);
        }

        if ($deadLetterRoutingKey !== null) {
            $queue->setArgument('x-dead-letter-routing-key', $deadLetterRoutingKey);
        }

        if ($messageTtl !== null) {
            $queue->setArgument('x-message-ttl', $messageTtl);
        }

        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();

        return $queue;
    }
}
