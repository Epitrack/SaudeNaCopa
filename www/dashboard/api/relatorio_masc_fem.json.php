<?php
include("../php/class.MySQL.php");
$conn = new MySQL();

$sql = "SELECT u.sexo as tipo,
		   ROUND(((count(u.sexo) * 100)/t.contagem),2) as percent 
		FROM usuarios u,
		(select count(sexo) as contagem from usuarios) t
		GROUP BY u.sexo";
$conn->ExecuteSQL($sql);
$dados = $conn->ArrayResults();

echo json_encode($dados);

?>