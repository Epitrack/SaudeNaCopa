<?php
 	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	$ll = $_POST['latLng'];
	$word = (isset($_POST['lista']) && $_POST['lista'] == "farmacias") ? "farmacia+OR+drogaria" : "hospitais";
	$url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$ll."&radius=10000&keyword=$word&sensor=false&key=AIzaSyCqn0s3iFP53fhhHB-6c2-zT5HTpoi8Lqk";
	$res = file_get_contents($url);
	echo $res;
?>