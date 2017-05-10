<?php 
$src = isset($_GET['src']) ? $_GET['src'] : '';
if($src != ""){
 
	$ch = curl_init($src);
 
	
	if (! $ch) {
		die( "cURL failed" );
	}
	 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	
	 
	$data = curl_exec($ch);
	
 
	curl_close($ch);
	$data .= '<style>.sinput,select,input[type=submit]{display:none}</style>';
	echo str_replace(array('<head>'),array('<head><base href="http://4.vndic.net" />'), $data);
}
?>