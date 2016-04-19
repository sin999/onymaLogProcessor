#!/usr/bin/php
<?php

include __DIR__."/classAutoLoader.php";
class OutWriter{
    function write($mes){
	echo json_encode($mes)."\n";
    }
}
$writer=new OutWriter();
$authProcessor= new AuthProcessor($writer);
$accProcessor= new AccProcessor($writer);
$errorProcessor= new ErrorProcessor($writer);

while($line = fgets(STDIN)){
    $inMes=json_decode($line);
    if(isset($inMes)){
	switch ($inMes->sourceType){
	    case Message::SOURCE_AUTH:
		$authProcessor->pushMessage($inMes);
		break;
	    case Message::SOURCE_ACC:
		$accProcessor->pushMessage($inMes);
		break;
	    default:;    
		$errorProcessor->pushMessage($inMes);
	}
    }
}

?>
