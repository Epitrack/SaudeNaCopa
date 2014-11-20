<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
		include("class.MySQL.php");
		$db = new MySQL();
		
		$db->ExecuteSQL("select unidades.id as id_unidade, unidades.nome as nome_unidade, 
						sub_unidades.id as id_sub_unidade, sub_unidades.nome as nome_sub_unidade 
						from unidades, sub_unidades
						where unidades.id = sub_unidades.unidade_id");
		$locais = $db->ArrayResults();
		
		foreach ($locais as $local){
			$array[] = array("id_unidade" => $local["id_unidade"], 
							 "nome_unidade" => utf8_encode($local["nome_unidade"]),
							 "id_sub_unidade" => utf8_encode($local["id_sub_unidade"]),
							 "nome_sub_unidade" => utf8_encode($local["nome_sub_unidade"])
							 );	
		}
		echo json_encode($array);
?>