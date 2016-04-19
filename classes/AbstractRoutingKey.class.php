<?php
class AbstractRoutingKey implements RoutingKey{

    var $fields = array();
    function __construct($field_count){
	array_pad($this->fields,$field_count,null);
    }	
    
    function get($ind){
	if(!isIndexValid($ind)){ throw new Exception('Out of bound exception. (AbstractRoutingKey)');}
	return $this->fields[$ind];
    }
    
    function put($ind,$value){
    	if(!isIndexValid($ind)){ throw new Exception('Out of bound exception. (AbstractRoutingKey)');}
	$this->fields[$ind]=$value;
    }
    
    function toString(){
	var $str_key="";
	for($i=0;$i<count($this->fields);$i++){
	    $str_key.=( ($i=0) ? "." : "" ) . (empty($this->fields[$i])) ? $this->fields[$i] : self::VOID_REPLACMENT;
	}
	return $srt_key;
    }
    
    function isIndexValid($ind){
	return is_int($ind) && $ind<count($this->fields);
    }
                           
}
?>
