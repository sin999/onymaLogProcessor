#!/usr/bin/php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$topicName="AUTH_LOG";
$server_addr="10.200.5.207";
$server_port="5672";
$login="guest";
$password="guest";
$connection = new AMQPStreamConnection($server_addr , $server_port, $login, $password);
$channel = $connection->channel();
$channel->exchange_declare($topicName, 'topic', false, false, false);

while($line = fgets(STDIN)){
    $obj=json_decode($line);
    if($obj!=null){
	$msg = new AMQPMessage(json_encode($obj->body));
	$channel->basic_publish($msg, $topicName, $obj->routingKey);
    }
}

$channel->close();
$connection->close();


?>
