#!/usr/bin/php
<?php
include __DIR__."/classAutoLoader.php";
class FlowConst
{
    const KEY_AUTH="auth";
    const KEY_ACC="acc";

}

if(count($argv)>2){
    $sourceAP=$argv['2'];
}else{
    $sourceAP = gethostname();
}

if(count($argv)>1){
    $sourceType=sourceType($argv['1']);
    while($line = fgets(STDIN)){
	echo $line."\n";
	$obj=parseLine($line,$sourceType);
	$obj->sourceType=$sourceType;
	$obj->sourceAP=$sourceAP;
	try{
	echo json_encode($obj)."\n";
	}catch(Exception $e){
	    echo $line;
	}
    }
} else {
    echo "Unknown flow type !\n";
    echo "The way to run: [lines source] |./flow2InMessages.php stream_type source_ap\n";
    echo "Example 1: cat ./test_auth_stream.txt | ./flow2InMessages.php auth\n";
    echo "Example 2: cat ./test_auth_stream.txt | ./flow2InMessages.php acc\n";
    echo "Example 3: ./infinitFlow.sh | ./flow2InMessages.php acc\n";
}

function sourceType($key){
    switch ($key){
	case FlowConst::KEY_AUTH:
	    return Message::SOURCE_AUTH;
	case FlowConst::KEY_ACC:
	    return Message::SOURCE_ACC;
	default:
	    return Message::SOURCE_UNKNOWN;
    }
}

function accLineToMessage($line){
    return $line;
}

function parseLine($line,$sourceType){
	switch ($sourceType) {
    	    case Message::SOURCE_AUTH:
		$mes=authLineToMessage($line);
		break;
    	    case Message::SOURCE_ACC:
		$mes=accLineToMessage($line);
		break;
	    default:
	        $mes=null;
	}
    return $mes;
}


function authLineToMessage($line){
    $line=str_replace("\\000","",$line);
    $dataArray=split("\t",trim($line));
    if(count($dataArray)>1){
        //preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S/', $line, $matches);
        //$dataArray=split("\t",trim($line));
        //$dataArray=array_merge(split(" ",$dataArray['0']),array_slice ($dataArray,1));
        $mes = new StdClass();
        $mes->datetime = getArrayIndex($dataArray,0,"");
        $mes->direction = getArrayIndex($dataArray,1,"");
        $mes->nasAddress = getArrayIndex($dataArray,2,"");
        $mes->seqid = getArrayIndex($dataArray,3,"");
        $mes->message=(getArrayIndex($dataArray,4,null)==null?null:line2obj(getArrayIndex($dataArray,4,"")));
        if(isset($dataArray['5'])){
            $mes->rejlogin=$dataArray['5'];
        }
    }
    return $mes;
}


function getArrayIndex($arr,$ind,$ifVoid){
    return (isset($arr)&&isset($arr[$ind]))?$arr[$ind]:$ifVoid;
}


function line2obj($line){
    $line=trim($line,"\n");
    $propertyKeyValSeparator="=";
    $fieldSeparator="&";
    $retObj=null;
    if(strlen($line)>0){
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

