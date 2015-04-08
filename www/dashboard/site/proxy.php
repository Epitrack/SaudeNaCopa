<?php
 header('Content-type: text/html; charset=utf-8');

$data1 = $_GET['data1'];
$data2 = $_GET['data2'];

$url = "http://www.saudenacopa.epitrack.com.br/dashboard/api/maps.json.range.php?data1=$data1&data2=$data2";
$dados = file_get_contents($url);

echo $dados;

?>