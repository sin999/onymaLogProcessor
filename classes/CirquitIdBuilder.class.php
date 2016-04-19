<?php
class CirquitIdBuilder{
    static $typeList = array();    
    static function addType($type){
	self::$typeList[]=$type;
    }

    static function makeObject($tag){
	$tag=trim($tag,'"');
	foreach(self::$typeList as $type){
	    if(($obj=$type->cirquitIdFactory($tag))!=null){
		return $obj;
	    }
	}
	return null;
    }
 
    function cirquitIdFactory($tag){
	$cirquitId = null;
	$arr=explode($this->SEPARATOR,$tag);
	if( isset($arr) && ( count($arr)==$this->FIELD_COUNT) ){
	    $cirquitId = new StdClass();
	    $cirquitId->clientMac= (isset($this->MAC_OFFSET))	   ? $arr[$this->MAC_OFFSET] 		: null;
	    $cirquitId->neName	 = (isset($this->NE_NAME_OFFSET))  ? $arr[$this->NE_NAME_OFFSET]	: null;
	    $cirquitId->neModule = (isset($this->NE_MODULE_OFFSET))? $arr[$this->NE_MODULE_OFFSET]	: null;
	    $cirquitId->nePort	 = (isset($this->NE_PORT_OFFSET))  ? $arr[$this->NE_PORT_OFFSET]	: null;
	}
	return $cirquitId;
    }

    static function test(){
	foreach(self::$typeList as $type){
	    foreach($type->examples as $tag){
		$obj = CirquitIdBuilder::makeObject($tag);
		print_r($obj);
	    }
	}
    }
}


class CirquitIdType1 extends CirquitIdBuilder{
	var $examples = array("FC:75:16:CC:33:91::STR-Kurch16-S1p3::5");
	// FC:75:16:CC:33:91::STR-Kurch16-S1p3::5
	var $SEPARATOR="::";
	var $FIELD_COUNT="3";
	var $MAC_OFFSET='0';
	var $NE_NAME_OFFSET='1';
	var $NE_MODULE_OFFSET=null;
	var $NE_PORT_OFFSET='2';
	
}

CirquitIdBuilder::addType(new CirquitIdType1());

class CirquitIdType2 extends CirquitIdBuilder{    
	// STR-PrOkt9-S1p1#3
	var $examples = array("STR-PrOkt9-S1p1#3");
	var $SEPARATOR="#";
	var $FIELD_COUNT="2";
	var $MAC_OFFSET=null;
	var $NE_NAME_OFFSET='0';
	var $NE_MODULE_OFFSET=null;
	var $NE_PORT_OFFSET='1';
}
CirquitIdBuilder::addType(new CirquitIdType2());
    
class CirquitIdType3 extends CirquitIdBuilder{
	// 172.24.6.1 eth 15
	var $examples = array("172.24.6.1 eth 15");
	var $SEPARATOR=" ";
	var $FIELD_COUNT="3";
	var $MAC_OFFSET=null;
	var $NE_NAME_OFFSET='0';
	var $NE_MODULE_OFFSET=null;
	var $NE_PORT_OFFSET='2';

}        
CirquitIdBuilder::addType(new CirquitIdType3());

CirquitIdBuilder::test();


?>
