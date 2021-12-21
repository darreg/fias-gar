<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPChannel;
use AMQPConnection;
use AMQPConnectionException;
use AMQPExchange;
use AMQPExchangeException;
use AMQPQueue;
use AMQPQueueException;

final class RabbitMqConnection
{
    private static ?AMQPConnection $connection = null;
    private static ?AMQPChannel    $channel    = null;
    /** @var AMQPExchange[] */
    private static array $exchanges = [];
    /** @var AMQPQueue[] */
    private static array $queues = [];
    private array        $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @throws AMQPQueueException
     * @throws AMQPConnectionException
     */
    public function queue(string $name): AMQPQueue
    {
        if (!\array_key_exists($name, self::$queues)) {
            $queue = new AMQPQueue($this->channel());
            $queue->setName($name);

            self::$queues[$name] = $queue;
        }

        return self::$queues[$name];
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPConnectionException
     */
    public function exchange(string $name): AMQPExchange
    {
        if (!\array_key_exists($name, self::$exchanges)) {
            $exchange = new AMQPExchange($this->channel());
            $exchange->setName($name);

            self::$exchanges[$name] = $exchange;
        }

        return self::$exchanges[$name];
    }

    /**
     * @throws AMQPConnectionException
     */
    private function channel(): AMQPChannel
    {
        if (self::$channel === null || !self::$channel->isConnected()) {
            self::$channel = new AMQPChannel($this->connection());
        }

        return self::$channel;
    }

    /**
     * @throws AMQPConnectionException
     */
    private function connection(): AMQPConnection
    {
        if (self::$connection === null) {
            self::$connection = new AMQPConnection($this->configuration);
        }

        if (!self::$connection->isConnected()) {
            self::$connection->pconnect();
        }

        return self::$connection;
    }
}
