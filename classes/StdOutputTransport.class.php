<?php
class StdOutputTransport implements Transport{
    function push($dialogue){
        echo $dialogue->toString()."\n";
    }
}
?>
