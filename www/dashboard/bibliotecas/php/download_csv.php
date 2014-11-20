<?php 
ob_start();
function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   
   $df = fopen("php://output", 'w');
   
   //fputcsv($df, array_keys(reset($array)));
   
   $contador=0;
   foreach ($array as $row) {
   		if($contador < 1){
   			fputcsv($df, $row);	
   		}
   		$contador++;
   }
   
   foreach ($array as $row) {
   		foreach ($row as $r) {
   				fputcsv($df, $r);
   		}
   }
   fclose($df);
   return ob_get_clean();
}


function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}


include "class.MySQL.php";
$conn = new MySQL();

$arquivo = 'planilha_download_saude_na_copa.xls';

$titulo = array("id", "apelido", "idade", "sexo", "email" , "data_hora", "hash", "gcmid", "idioma", "data_cadastro", "pontuacao",
"latitude", "longitude", "nome", "Febre", "Tosse", "Dor de Garganta", "Falta de ar", "Nausea e vomitos", "Diarreia", "Dor nas articulacoes",
"Dor de cabeca", "Sangramento","Manchas vermelhas no corpo", "Teve contato ou conhece alguem com algum desses sintomas nos ultimos 7 dias?",
"Voce procurou o serviço de saude?");

$sql = "SELECT u.id, u.apelido, u.idade, u.sexo, u.email , u.data_hora, 
u.hash, u.gcmid, u.idioma, us.data_cadastro, us.pontuacao,
us.latitude, us.longitude, c.nome, us.campo1, us.campo2, us.campo3,
us.campo4, us.campo5, us.campo6, us.campo7, us.campo8, us.campo9,
us.campo10, us.campo11, us.campo12
FROM usuarios u
INNER JOIN usuario_sentimento us ON us.usuario_id = u.id
INNER JOIN cidade c ON us.cidade_id = c.id";
$conn->ExecuteSQL($sql);
$array[0] = $titulo; 
$array[1] = $conn->ArrayResults();


download_send_headers("data_export_" . date("Y-m-d") . ".csv");
echo array2csv($array);
die();
?>