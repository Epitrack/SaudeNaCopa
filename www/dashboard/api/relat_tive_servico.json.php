<?php
include("../php/class.MySQL.php");
$conn = new MySQL();

$sql = "SELECT
			count(us.id) as total, 
			c.nome as nome, 
			sum(us.campo11) as campo11,
			sum(us.campo12) as campo12
			FROM usuario_sentimento us
			INNER JOIN cidade c ON c.id = us.cidade_id
			GROUP BY c.id";
$conn->ExecuteSQL($sql);
$dados = $conn->ArrayResults();

$sql = "select * from sentimentos";
$conn->ExecuteSQL($sql);
$sentimentos = $conn->ArrayResults();


foreach($dados as $dado){ 
	$total+=$dado["total"];

	$campo11 += $dado["campo11"];
	$campo12 += $dado["campo12"];
}

$contato = number_format(($campo11*100/$total));
$procurei = number_format(($campo12*100/$total));

$retono = array("tive_contato" => $contato, "procurei_servico" => $procurei);
echo json_encode($retono); 

?>