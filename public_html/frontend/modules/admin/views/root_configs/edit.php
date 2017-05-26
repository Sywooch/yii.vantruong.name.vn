<?php 
$fp = __DIR__ . '/_forms/' . getParam('code') . '.php';

if(file_exists($fp)){
	include_once $fp;
}

?>
