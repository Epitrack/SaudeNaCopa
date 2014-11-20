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
			$db->ExecuteSQL("select * from cidade");
		}else{
			$db->ExecuteSQL("select * from cidade where estado = " . $_POST["id"]);	
		}
		$cidades = $db->ArrayResults();
		foreach ($cidades as $cidade){
			$array[] = array("id" => $cidade["id"], "nome" => utf8_encode($cidade["nome"]) );	
		}
		echo json_encode($array);
?>