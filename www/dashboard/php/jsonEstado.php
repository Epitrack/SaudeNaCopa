<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
		include("class.MySQL.php");
		$db = new MySQL();
		if(!isset($_POST["id"])){
			$db->ExecuteSQL("select id,nome from estado");
		}else{
			$db->ExecuteSQL("select id,nome from estado where pais = " . $_POST["id"]);
		}	
		$paises = $db->ArrayResults();
		
		foreach ($paises as $pais){
			$array[] = array("id" => $pais["id"], "nome" => utf8_encode($pais["nome"]) );	
		}
		echo json_encode($array);
?>