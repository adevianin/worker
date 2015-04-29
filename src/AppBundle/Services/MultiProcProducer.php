<?php

namespace AppBundle\Services;

use PhpAmqpLib\Connection\AMQPConnection;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

class MultiProcProducer extends Producer
{
    private $connectionOptions;

    public function __construct($address, $port, $login, $pass)
    {
        $this->conn = new AMQPConnection($address, $port, $login, $pass);
    }

    public function setConnection(AMQPConnection $connection)
    {
        $this->conn = $connection;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function publish($msgBody, $routingKey = '', $additionalProperties = array())
    {
        $channel = $this->getChannel();
        parent::publish($msgBody, $routingKey, $additionalProperties);
        $channel->close();
    }
}
