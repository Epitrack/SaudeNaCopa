<?php
 header('Content-type: text/html; charset=utf-8');

$url = "http://www.saudenacopa.com/dashboard/api/relatorio.json.php";
$dados = file_get_contents($url);

echo $dados;

?>