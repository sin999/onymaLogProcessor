#!/usr/bin/php
<?php
//include "logProcessLib.php";
include "classAutoLoader.php";

$transport=new  StdOutputTransport();
$dialogueBuilder=new DialogBuilder($transport);
while($line = fgets(STDIN)){
    $dialogueBuilder->pushRawLine($line);
}


?>

