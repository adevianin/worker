<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ConvertAudioConsumer implements ConsumerInterface
{
    private $resultsProducer;

    private $logger;

    public function __construct($resultsProducer, $logger)
    {
        $this->resultsProducer = $resultsProducer;
        $this->logger = $logger;
    }

    public function execute(AMQPMessage $msg)
    {
        try {
            $data = unserialize($msg->body);
            $tempFilePath = __DIR__.'/../../../temp/'.$data['fileName'];
            $convertedFilePath = $tempFilePath.'.'.$data['format'];

            file_put_contents($tempFilePath, $data['fileContent']);

            $pid = pcntl_fork();
            if ($pid == 0) {
                exec(sprintf('ffmpeg -i %s %s.%s', $tempFilePath, $tempFilePath, $data['format']));

                $this->resultsProducer->publish(serialize(array(
                    'fileContent' => file_get_contents($convertedFilePath),
                    'fileName' => $data['fileName'],
                    'format' => $data['format'],
                )));

                unlink($tempFilePath);
                unlink($convertedFilePath);

                posix_setsid();
                posix_kill($pid, SIGKILL);
            }
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());

            if (isset($pid) && $pid === 0) {
                posix_setsid();
                posix_kill($pid, SIGKILL);
            }
        }
    }
}
