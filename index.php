<?php

require 'config.php';
require 'util/Auth.php';
//Autoloader
function __autoload($class){
	require DS_LIBS.$class.".php";
}
$bootstrap=new Bootstrap();
$bootstrap->init();
?>