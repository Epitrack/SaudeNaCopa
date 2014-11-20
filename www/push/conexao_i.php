<?php

$mysqli = new mysqli("", "", "", "");

if($mysqli->connect_errno > 0){
	die('Unable to connect to database [' . $mysqli->connect_error . ']');
}
?>