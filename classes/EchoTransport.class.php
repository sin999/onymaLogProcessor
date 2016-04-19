<?php
class EchoTransport implements Transport{
    function push($dialogue){
        echo $dialogue->toString()."\n";
    }
}
?>
