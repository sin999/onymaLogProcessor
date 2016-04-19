#!/usr/bin/php
<?php

while($line = fgets(STDIN)){
    echo getLine($line)."\n";
}

function getProp($obj,$propName){
    return isset($obj->$propName)?$obj->$propName:"  ";
}

function getLine($line){
    $obj=json_decode($line);
    return " ".getProp($obj,"User-Name")." ".getProp($obj,"circuit-id-tag")." ";
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
	    // 		property name          property value
		$retObj->{$propArr[0]} = trim($propArr[1],'"');
	    }
	}
    }
    return $retObj;
}
?>
