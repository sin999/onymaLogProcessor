#!/usr/bin/php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$topicName="AP_BASH2";

$connection = new AMQPStreamConnection( '10.200.5.207', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->exchange_declare($topicName, 'topic', false, false, false);

#$routing_key = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'anonymous.info';
#$routing_key = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'anonymous.info';
#$data = implode(' ', array_slice($argv, 2));
#if(empty($data)) $data = "Hello World!";

while($line = fgets(STDIN)){
    $obj=json_encode($line);
#    echo json_decode($obj);
    if($obj instanceof Object ){
	$msg = new AMQPMessage($obj->body);
	$channel->basic_publish($msg, 'AP_BASH2', $obj->routing_key);
    }
}



#echo " [x] Sent ",$routing_key,':',$data," \n";

$channel->close();
$connection->close();
