<?php 
include("../php/class.MySQL.php");
$db = new MySQL();

	$contador=0;
	$listagem = array();

 
		
		$subUnidade = "SELECT distinct(usuario_id) as id FROM usuario_sentimento us 
					   where usuario_id <> \"\" and usuario_id <> 0";
		$db->ExecuteSQL($subUnidade);
		$usuario_sentimento = $db->ArrayResults();	
		
		//0 = muito mal
		//1 = mal
		//2 = quemmm
		//3 = bem
		//5 = muito bem
		$semimentos = array("muitobem", "bem", "normal", "mal", "muitomal");
		
		foreach ($usuario_sentimento as $lista_sentimento){
			
			/*
			$subUnidade = "SELECT us.id as Id, us.latitude as Latitude, us.longitude as Longitude,  us.sentimento as Icone,
					   campo1, campo2, campo3, campo4, campo5, campo6, campo7, campo8, campo9, campo10
						FROM usuario_sentimento us";
			$db->ExecuteSQL($subUnidade);
			
			18/04/2014 - query
			$sub = "SELECT us.id as Id, us.latitude as Latitude, us.longitude as Longitude,  us.sentimento as Icone,
						   campo1, campo2, campo3, campo4, campo5, campo6, campo7, campo8, campo9, campo10, cidade_regiao_metro as regiao_metro
						   FROM usuario_sentimento us
					       where usuario_id = " . $lista_sentimento["id"] . " and cidade_regiao_metro <> ''
						   order by us.data_cadastro desc limit 0,1";
			
			*/
			$sub = "SELECT us.id as Id, us.latitude as Latitude, us.longitude as Longitude,  us.sentimento as Icone, us.data_cadastro as data_cadastro,
						   campo1, campo2, campo3, campo4, campo5, campo6, campo7, campo8, campo9, campo10, cidade_regiao_metro as regiao_metro
						   FROM usuario_sentimento us
					       where usuario_id = " . $lista_sentimento["id"] . "
						   order by us.data_cadastro desc limit 0,1";
			$db->ExecuteSQL($sub);
			$assistencias[] = $db->ArrayResults();
			
		}
		
		$contador=0;
		foreach ($assistencias as $lista){
			
			$listagem["locais"][$contador]["Id"] = $lista[0]["Id"];
			$listagem["locais"][$contador]["Latitude"] = $lista[0]["Latitude"];
			$listagem["locais"][$contador]["Longitude"] = $lista[0]["Longitude"];
			$listagem["locais"][$contador]["regiao_metro"] = $lista[0]["regiao_metro"];
			
			$data = new DateTime($lista[0]["data_cadastro"]);
			$dataEncontrado = $data->format("d-m-Y");
			$horaEncontrado = $data->format("H:i:s");
			
			$listagem["locais"][$contador]["data"] = $dataEncontrado;
			$listagem["locais"][$contador]["hora"] = $horaEncontrado;
			
			if($lista[0]["Icone"] != "2"){
				$listagem["locais"][$contador]["Icone"] = $semimentos[$lista[0]["Icone"]];
			}
			
			if($lista[0]["campo1"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "febre";	
			}
			if($lista[0]["campo2"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "tosse";	
			}
			if($lista[0]["campo3"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "DordeGarganta";	
			}
			if($lista[0]["campo4"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "FaltadeAr";	
			}
			if($lista[0]["campo5"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "NauseaeVomitos";	
			}
			if($lista[0]["campo6"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "Diarreia";	
			}
			if($lista[0]["campo7"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "DorNasArticulacoes";	
			}
			if($lista[0]["campo8"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "DordeCabeca";	
			}
			if($lista[0]["campo9"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "Sangramento";	
			}
			if($lista[0]["campo10"] == 1){
				$listagem["locais"][$contador]["Sintoma"][] = "ManchasVermelhasnoCorpo";	
			}
			$contador++;
		}
		echo json_encode($listagem);
?>