<?php

namespace AppBundle\Consumers;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class StatusConsomer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
    	var_dump(123);
    	$msg = new AMQPMessage(32, array('correlation_id' => $req->get('correlation_id')));
	
	    $req->delivery_info['channel']->basic_publish($msg, '', $req->get('reply_to'));
	    $req->delivery_info['channel']->basic_ack($req->delivery_info['delivery_tag']);
    }
}
