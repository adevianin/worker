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

        $convProgressFilePath = $this->getPathToTemp().$uid.'.progress';
        $fileInfoPath = $this->getPathToTemp().$uid.'.info';

        $progress = 0;
        if (file_exists($convProgressFilePath) && file_exists($fileInfoPath)) {
            $convertingProgress = parse_ini_file($convProgressFilePath);
            $fileInfo = parse_ini_file($fileInfoPath);

            $progress = $this->statusChecker->culcProgress($convertingProgress, $fileInfo);
        }

        return $progress;
    }

    private function getPathToTemp()
    {
        return __DIR__.'/../../../temp/';
    }
}
