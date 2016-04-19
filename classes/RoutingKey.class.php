<?php
interface RoutingKey{
    const VOID_REPLACMENT="*";
    function get($ind);
    function put($ind,$value);
    function toString();
}
?>
