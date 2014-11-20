<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
		include("class.MySQL.php");
		$db = new MySQL();
		
		$db->ExecuteSQL("select * from users");
		$users = $db->ArrayResults();
		
		foreach ($users as $user){
			
			if($user["ponto_monitoramento"] != 0) {
				
				$sql = "select * from sub_unidades where id = " . $user["ponto_monitoramento"];
				$db->ExecuteSQL($sql);
				$unidade = $db->ArrayResult();
				
			}else{
				$unidade["nome"] = "";
			}
			
			$array[] = array("id" => $user["id"], 
							 "nome" => utf8_encode($user["username"]),
							 "senha" => utf8_encode($user["password"]),
							 "email" => utf8_encode($user["email"]),
							 "ponto_monitoramento" => utf8_encode($user["ponto_monitoramento"]),
							 "unidade" => utf8_encode($unidade["nome"])
							 );	
		}
		echo json_encode($array);
?>