<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ConvertAudioConsumer implements ConsumerInterface
{
    private $resultsProducer;

    public function __construct($resultsProducer)
    {
        $this->resultsProducer = $resultsProducer;
    }

    public function execute(AMQPMessage $msg)
    {
        $data = unserialize($msg->body);
        $tempFilePath = __DIR__.'/../../../temp/'.$data['fileName'];
        $convertedFilePath = $tempFilePath.'.'.$data['format'];

        file_put_contents($tempFilePath, $data['fileContent']);

        exec(sprintf('ffmpeg -i %s %s.%s', $tempFilePath, $tempFilePath, $data['format']));

        $this->resultsProducer->publish(serialize(array(
            'fileContent' => file_get_contents($convertedFilePath),
            'fileName' => $data['fileName'],
            'format' => $data['format'],
        )));

        unlink($tempFilePath);
        unlink($convertedFilePath);
    }
}
