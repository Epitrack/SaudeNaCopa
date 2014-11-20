<?php 
ob_start();
ini_set('memory_limit', '-1');
 

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
          $r['LOC_REGISTRO']=utf8_decode($r['LOC_REGISTRO']);
           $r['REGIAO'] = utf8_decode($r['REGIAO']);
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
  

$sql = "SELECT ID_REG_SEQ, ID_USUARIO ,APELIDO,IDADE, SEXO, EMAIL, IDIOMA , DT_CADASTRO,
DT_REGISTRO_DIA, DT_REGISTRO_HORA,   LOC_REGISTRO  ,  REGIAO , EQUIPAMENTO,
LATITUDE ,LONGITUDE, STATUS, FEBRE, TOSSE, DORGARGANTA, FALTAR, NAUSEA,
DIARREIA, ARTRALGIA, CEFALEIA, HEMORRAGIA, EXANTEMA, CONTATO, SERVSAUDE,
CADASTRO, SINTOMA, SIND_DIA, SIND_RES, SIND_EXA from csv_leve";

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