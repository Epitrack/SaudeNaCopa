<?php 
include("bibliotecas/php/class.MySQL.php");
$db = new MySQL();

	$listagem = array();
		
		$subUnidade = "SELECT c.nome, us.latitude, us.longitude 
						FROM usuario_sentimento us
						INNER JOIN cidade c ON us.cidade_id = c.id";
		$db->ExecuteSQL($subUnidade);
		$assistencias = $db->ArrayResults();	
		
		$listagem[$listaSub["id"]]["locais"] = $assistencias;
		
		echo json_encode($listagem);	
?>