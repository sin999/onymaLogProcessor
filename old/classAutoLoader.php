<?php
function classAutoLoader($classname) {
    $filename = "./classes/". $classname .".class.php";
    include_once($filename);
}
spl_autoload_register('classAutoLoader');
?>