<?php
include("../php/class.MySQL.php");
$conn = new MySQL();


$sqlTotal = "SELECT count(id) as total FROM usuario_sentimento";
$conn->ExecuteSQL($sqlTotal);
$total = $conn->ArrayResult();
$totalTodos = (int)$total["total"];

$sql = "SELECT
			count(us.id) as total, 
			us.cidade_regiao_metro as nome,
			
			sum(us.campo1) as campo1, 
			sum(us.campo2) as campo2, 
			sum(us.campo3) as campo3,
			sum(us.campo4) as campo4,
			sum(us.campo5) as campo5,
			sum(us.campo6) as campo6,
			sum(us.campo7) as campo7,
			sum(us.campo8) as campo8,
			sum(us.campo9) as campo9,
			sum(us.campo10) as campo10,
			sum(us.campo11) as campo11,
			sum(us.campo12) as campo12
			FROM usuario_sentimento us
			
			where us.sentimento in (3,4)
			and us.cidade_regiao_metro <> ''
			
			GROUP BY us.cidade_regiao_metro";
$conn->ExecuteSQL($sql);
$dados = $conn->ArrayResults();

$sql = "select * from sentimentos";
$conn->ExecuteSQL($sql);
$sentimentos = $conn->ArrayResults();

	$campo1=0;
	foreach($dados as $dado){ 
	//$total+=$dado["total"];

		$campo1 += $dado["campo1"];
		$campo2 += $dado["campo2"];
		$campo3 += $dado["campo3"];
		$campo4 += $dado["campo4"];
		$campo5 += $dado["campo5"];
		$campo6 += $dado["campo6"];
		$campo7 += $dado["campo7"];
		$campo8 += $dado["campo8"];
		$campo9 += $dado["campo9"];
		$campo10 += $dado["campo10"];
		$campo11 += $dado["campo11"];
		$campo12 += $dado["campo12"];
	 }
	 
	 //$totalTodos = $campo1 + $campo2 + $campo3 + $campo4 + $campo5 + $campo6 + $campo7 + $campo8 + $campo9 + $campo10;
	 
	 //echo "<br>$campo12"; 
	 
	  	$retorno = array();
	 	$retorno["campo1"] = number_format(($campo1*100/$totalTodos));
		$retorno["campo2"] = number_format(($campo2*100/$totalTodos));
		$retorno["campo3"] = number_format(($campo3*100/$totalTodos));
		$retorno["campo4"] = number_format(($campo4*100/$totalTodos));
		$retorno["campo5"] = number_format(($campo5*100/$totalTodos));
		$retorno["campo6"] = number_format(($campo6*100/$totalTodos));
		$retorno["campo7"] = number_format(($campo7*100/$totalTodos));
		$retorno["campo8"] = number_format(($campo8*100/$totalTodos));
		$retorno["campo9"] = number_format(($campo9*100/$totalTodos));
		$retorno["campo10"] = number_format(($campo10*100/$totalTodos));
		$retorno["campo11"] = number_format(($campo11*100/$totalTodos));
		$retorno["campo12"] = number_format(($campo12*100/$totalTodos));

		//echo "<pre>";
	 	//var_dump($retorno);
		
		//JSON MASC E FEM
		$sql = "SELECT u.sexo as tipo,
				   ROUND(((count(u.sexo) * 100)/t.contagem),2) as percent 
				FROM usuarios u,
				(select count(sexo) as contagem from usuarios) t
				GROUP BY u.sexo";
		$conn->ExecuteSQL($sql);
		$dadosMasFem = $conn->ArrayResults();
		
		$retorno["campo_masc"] = $dadosMasFem[1]["percent"];
		$retorno["campo_fem"] = $dadosMasFem[0]["percent"];
		
		
		//JSON DEVICES 
		$sql = "SELECT u.id, u.device as nome, ROUND(((count(u.device) * 100)/t.contagem),2) as percent 
				FROM usuarios u,
				(select count(device) as contagem from usuarios, usuario_sentimento
				where usuario_sentimento.usuario_id = usuarios.id) t,
				usuario_sentimento us
				where us.usuario_id = u.id
				GROUP BY u.device";
		$conn->ExecuteSQL($sql);
		$dadosDevice = $conn->ArrayResults();
		
		$retorno["campo_device_android"] = $dadosDevice[0]["percent"];
		$retorno["campo_device_ios"] = $dadosDevice[2]["percent"];
		$retorno["campo_device_pc"] = $dadosDevice[3]["percent"];
		$retorno["campo_device_tablet"] = $dadosDevice[1]["percent"];
		
		echo json_encode($retorno);
		
?>


