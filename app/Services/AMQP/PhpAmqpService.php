<?php

namespace App\Services\AMQP;

use Closure;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class PhpAmqpService implements AMQPInterface
{

    public function producer(string $queue, array $payload, string $exchange): void
    {
       
    }

    public function producerFanout(array $payload, string $exchange): void
    {
        
    }

    public function consumer(string $queue, string $exchange, Closure $callback): void
    {
        
    }

}