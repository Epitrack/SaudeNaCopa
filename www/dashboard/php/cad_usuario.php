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

	//$resultado = array("status" => TRUE, "dados" => $_POST);
	
	//data: {'apelido': val_apelido, 'idade': val_idade, 'sexo': val_sexo, 'email': val_email, 'senha': val_senha, 'val_confirma_senha': campoCadastro_confirmacaoDeSenha},
	$apelido = $_POST["apelido"];
	$idade = $_POST["idade"];
	$sexo = $_POST["sexo"];
	$email = $_POST["email"];
	$senha = $_POST["senha"];
	$val_confirma_senha = $_POST["confirmacaoDeSenha"];
	$data = date("Y-m-d H:i:s");
	$termo = 1;
	
	$password = $auth->hashpass($senha);
	$verifypassword = $auth->hashpass($val_confirma_senha);
	//$activekey = $auth->randomkey(15);

		if($auth->validaCadastro($apelido, $password, $verifypassword, $email)){
			
			$hash = md5(microtime() . rand("1000", "9999"));
			
			$query = $auth->mysqli->prepare("INSERT INTO usuarios (apelido, idade, sexo, email, senha, termo, data_hora, hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$query->bind_param("ssssssss", $apelido, $idade, $sexo, $email, $password,$termo,$data, $hash);
			$teste = $query->execute();
			$query->close();
			
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