<?php
class AuthProcessor {
    const RESPONSE_TIMEOUT_INTERVAL_MSEC=1000;
    const REQUEST_PATTERN="IN[1]";
    const RESPONSE_PATTERN_SUCCESS="OUT[2]";
    const RESPONSE_PATTERN_FAIL="OUT[3]";
           
    var $requests = array();
    var $writer = null;
    
    function __construct($writer){
        $this->writer=$writer;
    }

    function pushRawLine($line){
        $this->pushMessage(AuthMessage::buildFromLine($line));
    }
    
    function getDialogueId($message){
	return isset($message->seqid)?$message->seqid:null;
    }

    function pushMessage($message){
        if(isset($message)){
	    $dialogueId = $this->getDialogueId($message);
            switch(isset($message->direction)?$message->direction:"unknown"){
                case self::REQUEST_PATTERN:
                    $this->requests[$dialogueId]=$message;
                    break;
                case self::RESPONSE_PATTERN_SUCCESS:
            	    $dialogue=$this->createDialogue($message);
                    $this->processDialogue($dialogue);
                    break;
                case self::RESPONSE_PATTERN_FAIL:
            	    $dialogue=$this->createDialogue($message);
                    $this->processDialogue($dialogue);
                    break;
            }
        }
    }
    
    function createDialogue($message){
	$dialogueId = $this->getDialogueId($message);
	$dialogue = new StdClass();
        $dialogue->response = $message;
        if(isset($this->requests[$dialogueId])){
    	    $dialogue->request = $this->requests[$dialogueId];
            unset($this->requests[$dialogueId]);
        }
        return $dialogue;    
    }
    
    function processDialogue($dialogue){
	$out_message= $this->createOutMessage($dialogue);
	if(isset($out_message)){
	    $this->processOutMessage($out_message);
	}
    
    }

    function processOutMessage($out_message){
        if(isset($this->writer)){
            $this->writer->write($out_message);
        }else{
            echo json_decode($out_message);
        }
    }
    
    function createOutMessage($dialogue){
	$outMes=null;
	if(isset($dialogue)){
	    $outMes = new StdClass();
	    $outMes->routingKey = $this->createRoutingKey($dialogue);
	    $outMes->body = $dialogue;
	}
	return $outMes;
    }
    
    function createRoutingKey($dialogue){
	$key="";
	$key .= "".$this->normStr1($this->ap4key($dialogue)); 		// 1 AP (radius server)
	$key .= ".".$this->normStr1($this->nas4key($dialogue));		// 2 BRAS
	$key .= ".".$this->normStrRes($this->result4key($dialogue));	// 3 Result (success, fail)
	$key .= ".".$this->normStr1($this->userName4key($dialogue));	// 4 UserName
	$key .= ".".$this->normStrIP($this->userIp4key($dialogue));	// 5 Client Ip  addres
	$key .= ".".$this->normStrMAC($this->userMac4key($dialogue));	// 6 Client Mac
	$key .= ".".$this->normStrCirquitId($this->cirquitId4key($dialogue));	// 7 Cirquit ID
	return $key;	
    }
    
    function normStr1($str){
	$str=base64_encode($str);
	return $str;
    }
    
    function normStrCirquitId($str){
	$str=CirquitIdBuilder::makeObject($str);
	$neName=isset($str->neName)?$str->neName:"unknown";
	return base64_encode($neName);
    }
    
    function normStrRes($str){
	return $str;
    }

    function normStrIP($str){
	return ip2long($str);
    }

    function normStrMAC($str){
	$str = strtolower($str);
	$str =preg_replace('/[^0-9a-f]/',"",$str);
	return $str;
    }
    
    function ap4key($dialogue){
	if(isset($dialogue->request)&& isset($dialogue->request->sourceAP)){
	    $ap = $dialogue->request->sourceAP;
	}else{
	    $ap = (isset($dialogue->response)&& isset($dialogue->response->sourceAP))?$dialogue->response->sourceAP:"unknown";
	}
	return $ap;
    }
    
    function nas4key($dialogue){
	$nas = (isset($dialogue->request)&& isset($dialogue->request->message) && isset($dialogue->request->message->{'D-NAS-Name'}))
		    ?$dialogue->request->message->{'D-NAS-Name'}:"unknown";
	return $nas;
    }
    function result4key($dialogue){
	$nas = (isset($dialogue->response)&& isset($dialogue->response->direction) )
		    ?$dialogue->response->direction:"unknown";
	return $nas;
    }
    
    function userName4key($dialogue){
	$nas = (isset($dialogue->request)&& isset($dialogue->request->message) && isset($dialogue->request->message->{'User-Name'}))
		    ?$dialogue->request->message->{'User-Name'}:"unknown";
	return $nas;
    }

    function userMac4key($dialogue){
	$nas = (isset($dialogue->request)&& isset($dialogue->request->message) && isset($dialogue->request->message->{'client-mac-address'}))
		    ?$dialogue->request->message->{'client-mac-address'}:"unknown";
	return $nas;
    }


    function userIp4key($dialogue){
	$nas = (isset($dialogue->request)&& isset($dialogue->request->message) && isset($dialogue->request->message->{'framed-ip-addres'}))
		    ?$dialogue->request->message->{'framed-ip-addres'}:"unknown";
	return $nas;
    }

    function cirquitId4key($dialogue){
	$nas = (isset($dialogue->request)&& isset($dialogue->request->message) && isset($dialogue->request->message->{'circuit-id-tag'}))
		    ?$dialogue->request->message->{'circuit-id-tag'}:"unknown";
	return $nas;
    }
}

?>
