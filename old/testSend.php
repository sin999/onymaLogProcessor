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
    $obj=line2obj($line);
    if($obj!=null){
	$routing_key = createRoutingKey($obj); 
	$data = json_encode($obj);
	$msg = new AMQPMessage($data);
	$channel->basic_publish($msg, 'AP_BASH2', $routing_key);
    }
}



#echo " [x] Sent ",$routing_key,':',$data," \n";

$channel->close();
$connection->close();

function createRoutingKey($obj){
    $key="";
    $key.="".(isset($obj->{'User-Name'})?$obj->{'User-Name'}:"*"); 
    $key.=".".str_replace(".", "",(isset($obj->{'client-mac-address'})?$obj->{'client-mac-address'}:"*")); 
    $key.=".".(isset($obj->{'circuit-id-tag'})?$obj->{'circuit-id-tag'}:"*"); 
    return $key;
}

function line2obj($line){
    $line=trim($line,"\n");
    $targetLineStart="D-Date";
    $propertyKeyValSeparator="=";
    $fieldSeparator="&";
    $retObj=null;
    if(substr($line, 0, strlen($targetLineStart)) === $targetLineStart){
        $retObj=new stdClass();
        $fieldArray=split($fieldSeparator,$line);
        foreach($fieldArray as $propKeyVal) {
            $propArr=split($propertyKeyValSeparator,$propKeyVal);
            if(count($propArr)>1){
            //          property name          property value
                $retObj->{$propArr[0]} = trim($propArr[1],'"');
            }
        }
    }
    return $retObj;
}


?>
