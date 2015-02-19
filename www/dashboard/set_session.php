<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include "bibliotecas/php/class.MySQL.php";
include "bibliotecas/php/class.Auth.php";

$conn = new MySQL();
$auth = new Auth();

$auth->notLogged("login.php");

$validar = (isset($_POST["marcar_padrao"]))? 1 : 0;

$sql = "update data_padrao set data_inicio='" . $auth->trataDataBanco($_POST["data_inicio"]) . " 00:00:00', 
		data_final='" . $auth->trataDataBanco($_POST["data_final"]) . " 23:59:59', validar=$validar";
$conn->ExecuteSQL($sql);
	
$auth->setSession($_POST["data_inicio"], $_POST["data_final"], $validar, true);

//if($_POST["data_inicio"] != ""){
//	$auth->setSession($_POST["data_inicio"], $_POST["data_final"], $_POST["validar"], false);
//}
?>