<?php

namespace App\Services\AMQP;

use Closure;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class PhpAmqpService implements AMQPInterface
{
    protected $connection = null;
    protected $channel = null;

    public function __construct()
    {
        if ($this->connection) {
            return;
        }

        $configs = config('microservices.rabbitmq.hosts')[0];

        $this->connection = new AMQPStreamConnection(
            host: $configs['host'],
            port: $configs['port'],
            user: $configs['user'],
            password: $configs['password'],
            vhost: $configs['vhost']
        );

        $this->channel = $this->connection->channel();
    }

    public function producer(string $queue, array $payload, string $exchange): void {}

    public function producerFanout(array $payload, string $exchange): void
    {
        $this->channel->exchange_declare(
            exchange: $exchange,
            type: AMQPExchangeType::FANOUT,
            passive: false,
            durable: true,
            auto_delete: false,
        );

        $message = new AMQPMessage(
            json_encode($payload),
            [
                'content_type' => 'text/plain',
            ]
        );

        $this->channel->basic_publish($message);

        $this->closeChannel();
        $this->closeConnection();
    }

    public function consumer(string $queue, string $exchange, Closure $callback): void {}

    private function closeChannel(): void
    {
        $this->channel->close();
    }

    private function closeConnection(): void
    {
        $this->connection->close();
    }
}
