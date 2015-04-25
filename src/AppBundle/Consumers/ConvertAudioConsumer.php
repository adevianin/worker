<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ConvertAudioConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        $data = unserialize($msg->body);
        $tempFilePath = __DIR__.'/../../../temp/'.$data['fileName'];

        file_put_contents($tempFilePath, $data['fileContent']);

        exec(sprintf('ffmpeg -i %s %s.%s', $tempFilePath, $tempFilePath, $data['format']));

        unlink($tempFilePath);
    }
}
