<?php
class ErrorProcessor {
    var $writer=null;
    function __construct($writer){
        $this->writer=$writer;
    }
    
    function pushMessage($message){
	echo $message;
    }
}

?>
