<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */

include("../php/class.MySQL.php");
include("../php/class.Auth.php");

$conn = new MySQL();
$auth = new Auth();

	header("Content-type: application/json; charset=utf-8");
	echo $auth->validaLogin();

?>