<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */

include "class.MySQL.php";
include "class.Auth.php";

$conn = new MySQL();
$auth = new Auth();

	header("Content-type: application/json; charset=utf-8");

	$email = $_POST["email"];
	$senha = $_POST["senha"];
	$password = $auth->hashpass($senha);
	//$activekey = $auth->randomkey(15);

		if($auth->validaCadastroLogin($password, $email)){
			
			$conn->ExecuteSQL("select * from usuarios where email = '$email'");
			$lista = $conn->ArrayResult();
			
			$resultado = array("status" => TRUE, "hash" => $hash);
			
		}else{
			$erros = "";
			foreach($auth->errormsg as $erro){
				$erros .= $erro;	
			}
			$resultado['status'] = FALSE;
			$resultado['mensagem'] = "Mensagem de erro. ".$erros . " " . time();		
		}	
	
	echo json_encode($resultado);
?>