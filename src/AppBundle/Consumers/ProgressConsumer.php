<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ProgressConsumer implements ConsumerInterface
{
    private $statusChecker;

    public function __construct($statusChecker)
    {
        $this->statusChecker = $statusChecker;
    }

    public function execute(AMQPMessage $msg)
    {
        $uid = (string) unserialize($msg->body);

        $convertingProgress = parse_ini_file($this->getPathToTemp().$uid.'.progress');
        $fileInfo = parse_ini_file($this->getPathToTemp().$uid.'.info');

        return $this->statusChecker->culcProgress($convertingProgress, $fileInfo);
    }

    private function getPathToTemp()
    {
        return __DIR__.'/../../../temp/';
    }
}
