<?php
class AuthMessage implements Message{
    const REQUEST_PATTERN="IN[1]";
    const RESPONSE_PATTERN="OUT[3]";
    static function buildFromLine($line){
        $line=trim($line);
        $retval=null;
        if(isset($line) && strlen($line)>0){
            $retval= new AuthMessage($line);
        }
        return $retval;
    }

    function toString(){
        return json_encode($this);
    }

    function getType(){
        switch (isset($this->direction)?$this->direction:"unknown_direction") {
            case self::REQUEST_PATTERN:
                return Message::TYPE_AUTH_REQUEST;
            case self::RESPONSE_PATTERN:
                return self::TYPE_AUTH_RESPONSE;
        }
        return Message::TYPE_UNKNOWN;
    }

    function getDialogueId(){
        return isset($this->seqid)?$this->seqid:null;
    }

    function __construct($line){
        $dataArray=split(" ",preg_replace('/\s+/', ' ',trim($line)));
        if(count($dataArray)>1){
            preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S/', $line, $matches);
            $dataArray=split("\t",trim($line));
            $dataArray=array_merge(split(" ",$dataArray['0']),array_slice ($dataArray,1));
            $this->datetime = self::getArrayIndex($dataArray,0,"")." ".self::getArrayIndex($dataArray,1,"")." ".self::getArrayIndex($dataArray,2,"")." ".self::getArrayIndex($dataArray,3,"");
            $this->direction = self::getArrayIndex($dataArray,4,"");
            $this->nasAddress = self::getArrayIndex($dataArray,5,"");
            $this->seqid = self::getArrayIndex($dataArray,6,"");
            $this->message=(self::getArrayIndex($dataArray,7,null)==null?null:self::line2obj(self::getArrayIndex($dataArray,7,"")));
            if(isset($dataArray['8'])){
                $this->rejlogin=$dataArray['8'];
            }
        }
    }

    static function getArrayIndex($arr,$ind,$ifVoid){
        return (isset($arr)&&isset($arr[$ind]))?$arr[$ind]:$ifVoid;
    }

    static function line2obj($line){
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

}
?>
