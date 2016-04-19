<?php
class AuthRoutingKey extends AbstractRoutingKey{
    const VALUES_COUNT=8; // [0] SRC_AP, [1] SRC_BRASS, [2] TYPE, [3] User-Name, [4] IP, [5] MAC, [6] CirquitId, [7] DialogueResult;
    const SRC_AP_INDEX 		= 0;
    const SRC_BRASS_INDEX 	= 1;
    const TYPE_INDEX 		= 2;
    const UserName 		= 3;
    const IP_INDEX 		= 4;
    const MAC_INDEX 		= 5;
    const CirquitId_INDEX 	= 6;
    const DialogueResult_INDEX 	= 7;
    function __construct($auth_dialogue){
	parent::__construct(VALUES_COUNT);
	$this->fillFields($auth_dialogue){
    }	
    
    function fillFields($auth_dialogue){
	$this->put(SRC_AP_INDEX,)
    }
}
?>
