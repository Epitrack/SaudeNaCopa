<?php
 	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	$url = "http://www.gamfig.com/twitter/?id=minsaude";
	$res = file_get_contents($url);
	echo $res;
?>