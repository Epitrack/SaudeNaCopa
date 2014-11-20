<?php 
ob_start();
ini_set('memory_limit', '-1');
//0 = muito mal
//1 = mal
//2 = quemmm
//3 = bem
//5 = muito bem


function array2csv(array &$array, $conn)
{
	
   $sentimentos = array("muitobem", "bem", "normal", "mal", "muitomal");	
   if (count($array) == 0) {
     return null;
   }
   
   $df = fopen("php://output", 'w');
    
   $contador=0;
   foreach ($array as $row) {
   		if($contador < 1){
        //if(is_array($r))
   			 fputcsv($df, $row, ";");	
   		}else{
        foreach ($row as $r) {
          fputcsv($df, $r, ";");  
        }
      }
   		$contador++;
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

$titulo = array(
  "ID_REG_SEQ","ID_USUARIO",
   "APELIDO", "IDADE", "SEXO", "EMAIL" , "IDIOMA", "DT_CADASTRO","DT_REGISTRO_DIA","DT_REGISTRO_HORA", 
 "LOC_REGISTRO","REGIAO", "EQUIPAMENTO",
"LAT", "LONG", 
"STATUS",
 "FEBRE", "TOSSE", "DORGARGANTA", "FALTAR", "NAUSEA", "DIARREIA", "ARTRALGIA",
"CEFALEIA", "HEMORRAGIA","EXANTEMA", "CONTATO",
"SERVSAUDE", "CADASTRO", "SINTOMA", //"CONTAGEM",
"SIND_DIA","SIND_RES","SIND_EXA");//, "ATUALIZA");

/*
$sql = "SELECT u.id, u.apelido, u.idade, u.sexo, u.email , u.data_hora, 
u.hash, u.gcmid, u.idioma, us.data_cadastro, us.pontuacao,
us.latitude, us.longitude, c.nome, us.campo1, us.campo2, us.campo3,
us.campo4, us.campo5, us.campo6, us.campo7, us.campo8, us.campo9,
us.campo10, us.campo11, us.campo12
FROM usuarios u
INNER JOIN usuario_sentimento us ON us.usuario_id = u.id
INNER JOIN cidade c ON us.cidade_id = c.id";
*/ 

$sql = "SELECT 
us.id as ID_REG_SEQ,
us.usuario_id as ID_USUARIO, 
u.apelido as APELIDO, 
u.idade as IDADE,
u.sexo as SEXO, 
u.email as EMAIL,
u.idioma as IDIOMA, 
us.data_cadastro as DT_CADASTRO,
Date(us.data_cadastro) as DT_REGISTRO_DIA,
Time(us.data_cadastro) as DT_REGISTRO_HORA,
us.cidade_id as LOC_REGISTRO,
us.cidade_regiao_metro as REGIAO,
u.device as EQUIPAMENTO,
us.latitude as LATITUDE, 
us.longitude as LONGITUDE, 
stat.nome as STATUS,
2 - us.campo1  as FEBRE, 
2 - us.campo2  as TOSSE, 
2 - us.campo3  as DORGARGANTA,
2 - us.campo4  as FALTAR, 
2 - us.campo5  as NAUSEA, 
2 - us.campo6  as DIARREIA, 
2 - us.campo7  as ARTRALGIA, 
2 - us.campo8  as CEFALEIA, 
2 - us.campo9  as HEMORRAGIA,
2 - us.campo10 as EXANTEMA, 
2 - us.campo11 as CONTATO, 
2 - us.campo12 as SERVSAUDE, 
CONVERT_TZ(u.data_hora,'+00:00','+4:00') as CADASTRO,
2 - (us.sentimento > 2 ) as SINTOMA, 
 


2 - ((us.campo1 + us.campo5 + us.campo6) = 3)  as SIND_DIA,
2 - ((us.campo1 + us.campo2 + us.campo3) = 3) as SIND_RES,
2 - ((us.campo1 + us.campo2 + us.campo7+ us.campo8 + us.campo10) = 5) as SIND_EXA
 
FROM usuarios u
INNER JOIN usuario_sentimento us ON us.usuario_id = u.id
INNER JOIN csv_status stat ON stat.id = us.sentimento
 
order by us.usuario_id, us.data_cadastro";




//,

//(2 -  ( select count(*) from lastInsert lst where lst.`usuario_id` = us.usuario_id )) as ATUALIZA
/*
SELECT us.id,apelido, idade,  usuario_id, COUNT(us.id) as total 
FROM usuario_sentimento us 
inner join usuarios u on u.id= `usuario_id`
group by `usuario_id` ORDER BY `us.usuario_id` desc
*/

$sql = "select * from csv_leve";

$conn->ExecuteSQL($sql);
$array[0] = $titulo; 
$array[1] = $conn->ArrayResults();

$recife = new DateTimeZone("America/Recife");	
$HoraBrasilia = new DateTime();
$HoraBrasilia->setTimezone($recife);
$Hora = $HoraBrasilia->format("H_i_s");

//download_send_headers("SaudeNaCopa_" . date("Y-m-d-H-i-s") . ".csv");
download_send_headers("saudenacopa_" . date("Y_m_d") . "_" . $Hora . ".csv");
echo array2csv($array,$conn);
die();
?>