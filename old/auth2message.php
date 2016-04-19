#!/usr/bin/php
<?php
$inMessagesArray = array();

while($line = fgets(STDIN)){
    $obj=auth2obj($line);
    if($obj!=null && ($obj->direction=="IN[1]" || $obj->direction=="OUT[3]")){
	if($obj->direction=="IN[1]"){
	    $inMessagesArray[$obj->seqid]=$obj;
	}
	if($obj->direction=="OUT[3]"){
	    $obj->request=$inMessagesArray[$obj->seqid];
	}
        $message= new stdClass();
        $message->routing_key=createRoutingKey($obj);
        $message->body=$obj;
        $data = json_encode($message);
        echo $data."\n";
    }
}
                                            
function createRoutingKey($obj){
    $key="";
    $key.="".(isset($obj->message)?"*":$obj->message["Reply-Message"]);
    $key.=".".((getUserName($obj)==null)?"*":getUserName($obj)); 
    $key.=".".((getMac($obj)==null)?"*":getMac($obj));; 
    $key.=".".$obj->direction;
    return $key;
}                                            


function getRequest($obj){
    return ($obj->direction=="OUT[3]")?$obj->request:$obj;
}

function getUserName($obj){
    $obj=getRequest($obj);
    return isset($obj->message->{"User-Name"})?$obj->message->{"User-Name"}:null;
}

function getMac($obj){
    $obj=getRequest($obj);
    return isset($obj->message->{"client-mac-address"})?str_replace(".","",$obj->message->{"client-mac-address"}) : null;
}

function auth2obj($line){
//    $dataArray=split(" ",preg_replace('/\s+/', ' ',trim($line)));
//    preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S/', $line, $matches);
//    print_r($matches);
    $dataArray=split("\t",trim($line)	);
    $dataArray=array_merge(split(" ",$dataArray['0']),array_slice ($dataArray,1));
    $result = new stdClass();
    $result->datetime = $dataArray['0']." ".$dataArray['1']." ".$dataArray['2'];
    $result->direction = $dataArray['3'];
    $result->nasAddress = $dataArray['4'];
    $result->seqid = $dataArray['5'];
    $result->message=($dataArray['6']==null?null:line2obj($dataArray['6']));
    if(isset($dataArray['7'])){
	$result->rejlogin=$dataArray['7'];
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

