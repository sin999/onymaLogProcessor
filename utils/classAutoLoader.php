<?php
function classAutoLoader($classname) {
    $filename = __DIR__."/../classes/". $classname .".class.php";
    include_once($filename);
}
spl_autoload_register('classAutoLoader');
?>