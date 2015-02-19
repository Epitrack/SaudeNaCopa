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

	$hash = $_POST["hash"];
	$latitude = $_POST["latitude"];
	$longitude = $_POST["longitude"];
	$ativo = 1;
	$pontos = $auth->getValorPontuacao();
	
	if(isset($_POST["estou_bem"])){
		
		$sentimento = "0";
		$dataAtual = date("Y-m-d");
		
		$query = $auth->mysqli->prepare("INSERT INTO usuario_sentimento (usuario_id, sentimento_id, data_cadastro, jogo_ok, pontuacao, latitude, longitude, ativo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$query->bind_param("sssssss", $hash, $sentimento, $dataAtual,$pontos["jogo_ok"], $pontos["pontos"], $latitude,$longitude, $ativo);
		$teste = $query->execute();
		$query->close();
			
		$sql = "select * from usuario_sentimento where usuario_id = '$hash'";
		$conn->ExecuteSQL($sql);
		$retornoListagem = $conn->ArrayResults();
		
		$arrayLista = array();
		foreach($retornoListagem as $lista){
			
			$arrayLista[] = $lista;
			
		}
		$status = TRUE;
		$resultado = array("status" => $status, "listagem" => $arrayLista);
			
	}else{
		
		
		
	}
		
	echo json_encode($resultado);
?>