#!/usr/bin/php
<?php
$inMessagesArray = array();

while($line = fgets(STDIN)){
    $obj=auth2obj($line);
    if($obj!=null){

	if($obj->direction=="IN[1]"){
	    $inMessagesArray[$obj->seqid]=$obj;
	}
	if($obj->direction=="OUT[3]"){
	    $obj->request=$inMessagesArray[$obj->seqid];
	}
	
//        $routing_key = createRoutingKey($obj);
        $data = json_encode($obj);
        echo $data."\n";
//        $msg = new AMQPMessage($data);
//	$channel->basic_publish($msg, 'AP_BASH2', $routing_key);
    }
}
                                            


function createRoutingKey($obj){
	$request=null;
	if($obj->direction=="IN[1]"){
	    $request=$obj;
	}
	if($obj->direction=="OUT[3]"){
	    $request=$obj->request;
	}

    $key=	"" .(($param=getParam($request,"User-Name"))==null)?"*":$param)
		".".(($param=getParam($request,""))==null)?"*":$param)
		"."

}                                            

function getParam($obj,$paramName){
    return isset($obj[$paramName])?$obj[$paramName]:null;
}

function auth2obj($line){
    $dataArray=split(" ",preg_replace('/\s+/', ' ',trim($line)));
    preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S/', $line, $matches);
//    print_r($matches);

    $result=$dataArray=split("\t",trim($line));

    $dataArray=array_merge(split(" ",$dataArray['0']),array_slice ($dataArray,1));
    $result = new stdClass();
    $result->datetime = $dataArray['0']." ".$dataArray['1']." ".$dataArray['2']." ".$dataArray['3'];
    $result->direction = $dataArray['4'];
    $result->nasAddress = $dataArray['5'];
    $result->seqid = $dataArray['6'];
    $result->message=($dataArray['7']==null?null:line2obj($dataArray['7']));
    if(isset($dataArray['8'])){
	$result->rejlogin=$dataArray['8'];
    }

    return($result);
}

function line2obj($line){
    $line=trim($line,"\n");
//    $targetLineStart="D-Date";
    $propertyKeyValSeparator="=";
    $fieldSeparator="&";
    $retObj=null;
//    if(substr($line, 0, strlen($targetLineStart)) === $targetLineStart){
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

