<?php
class Dialogue{
    var $srcAp;
    var $request;
    var $response;
    function __construct($request,$response,$srcAp){
        $this->request=$request;
        $this->response=$response;
    }

    function toString(){
        return json_encode($this);
    }
}
?>
