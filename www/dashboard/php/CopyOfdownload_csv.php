<?php 
ob_start();
ini_set('memory_limit', '-1');
//0 = muito mal
//1 = mal
//2 = quemmm
//3 = bem
//5 = muito bem


function array2csv(array &$array, $conn, $arquivo)
{
	
   $sentimentos = array("muitobem", "bem", "normal", "mal", "muitomal");	
   if (count($array) == 0) {
     return null;
   }
   
   //$df = fopen("php://output", 'a+');
   $df = fopen($arquivo, 'a+');
   
   //fputcsv($df, array_keys(reset($array)));

  // fputcsv($df, $titulo);
   if(file_exists($arquivo)){

   	   $contador=0;
	   foreach ($array as $row) {
	   		if($contador < 1){
	        //if(is_array($r))
	   			 fputcsv($df, $row, ";");	
	   		}
	   		$contador++;
	   }
   }
   
   $contadorContagem=1;
   $contadorUsuario=1;
   $atualiza=1;
   $aux=0;
   $contadorErro=0;
   $teste=1;
   
   foreach ($array as $row) {
   		foreach ($row as $r) {
	        
   			if(is_array($r)){
					
   					if($contadorUsuario==1){
	   					//echo "<BR>##ENTROU AQUI###<br>";	
						$sqlUsuario = "SELECT COUNT(Id) as total FROM usuario_sentimento WHERE usuario_id = " . $r['usuario_id'];
						$conn->ExecuteSQL($sqlUsuario);
						$usuarioTotal = $conn->ArrayResult();
   					}
					
					if($usuarioTotal["total"] == $contadorUsuario){
						$atualiza = 1;
						$contadorUsuario=1;
					}else{
						$atualiza = 2;
						$contadorUsuario++;
					}
					//echo "<br>COUNT: " . $usuarioTotal["total"] . " ATUALIZA: " . $atualiza . " CONTAGEM: " . $contadorUsuario . "<br>";
					
			  $recife = new DateTimeZone("America/Recife");	
	          $novaHoraBrasilia = new DateTime($r['data_cadastro']);
	          $novaHoraBrasilia->setTimezone($recife);
	          $novaHora = $novaHoraBrasilia->format("H:i:s");	
	        	
	          $r['data_cadastro_hora'] = $novaHora;	
	          $varSentimento = $r['sentimento'];
	          $r['sentimento'] = $sentimentos[$r['sentimento']];
	          $r['apelido'] = utf8_decode( $r['apelido']  );	
	          $r['cidade'] = utf8_decode( $r['cidade']  );
	          $r['regiao'] = utf8_decode( $r['regiao']  );
	          
	          $r['campo1'] = ($r['campo1']==1)? "1" : "2";
	          $r['campo2'] = ($r['campo2']==1)? "1" : "2";
	          $r['campo3'] = ($r['campo3']==1)? "1" : "2";
	          $r['campo4'] = ($r['campo4']==1)? "1" : "2";
	          $r['campo5'] = ($r['campo5']==1)? "1" : "2";
	          $r['campo6'] = ($r['campo6']==1)? "1" : "2";
	          $r['campo7'] = ($r['campo7']==1)? "1" : "2";
	          $r['campo8'] = ($r['campo8']==1)? "1" : "2";
	          $r['campo9'] = ($r['campo9']==1)? "1" : "2";
	          $r['campo10'] = ($r['campo10']==1)? "1" : "2";
	          $r['campo11'] = ($r['campo11']==1)? "1" : "2";
	          $r['campo12'] = ($r['campo12']==1)? "1" : "2";
	          
	          
	          if($varSentimento >= 3){
	          	$r['sintoma'] = 1;	
	          }else{
	          	$r['sintoma'] = 2;
	          }
	          
	          if($aux == $r['usuario_id']){
	          	$contadorContagem++; 
	          	$r['contagem'] = $contadorContagem;
	          }else{
	          	$aux = $r['usuario_id'];
	          	$r['contagem'] = 1;
	          	$contadorContagem = 1;
	          }
	          
	          
	          $novaDataBrasilia = new DateTime($r['data_hora']);
	          $novaDataBrasilia->setTimezone($recife);
	          $novaData = $novaDataBrasilia->format("Y-m-d h:i:s");	
	          $r['data_hora'] = $novaData;
	          
	          
   			  $r['sind_dia']=2;
	          $r['sind_res']=2;
	          $r['sind_exa']=2;
	          //FEBRE
	          if($r['campo1']==1){
	          	//NAUSEA/VOMITO
	          	if($r['campo5']==1){
	          		//+ Diarreia
	          		if($r['campo6']==1){
	          			$r['sind_dia']=1;
	          		}
	          	}

				//TOSSE 
	          	if($r['campo2']==1){
	          		// + Dor de garganta
	          		if($r['campo3']==1){
	          			$r['sind_res']=1;
	          		}
	          		
	          		//DOR NAS ARTICULACOES		
	          		if($r['campo7']==1){
	          			//DOR DE CABECA
	          			if($r['campo8']==1){
	          				//EXANTEMA
	          				if($r['campo10']==1){
		          				$r['sind_exa']=1;
	          				}	
	          			}
	          		}
	          	}	          	
	          }
	          
	          $r['atualiza'] = $atualiza;
	          
	          fputcsv($df, $r, ";");
	        }
          
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
    
    readfile($filename);
}


include "class.MySQL.php";
$conn = new MySQL();

$sql = "SELECT COUNT(us.id) as total FROM usuarios u
		INNER JOIN usuario_sentimento us ON us.usuario_id = u.id";
$conn->ExecuteSQL($sql);
$total = $conn->ArrayResult();

$sql = "SELECT total FROM total_usuario_sentimento";
$conn->ExecuteSQL($sql);
$total_sentimento = $conn->ArrayResult();
$total_sentimento["total"];

$sql = "UPDATE total_usuario_sentimento SET total= " . $total["total"];
//$conn->ExecuteSQL($sql);

$listagem = $total["total"] - $total_sentimento["total"];
//echo "<br>";

$titulo = array(
  "ID_REG_SEQ","ID_USUARIO",
   "APELIDO", "IDADE", "SEXO", "EMAIL" , "IDIOMA", "DT_CADASTRO","DT_REGISTRO_DIA","DT_REGISTRO_HORA", 
 "LOC_REGISTRO","REGIAO", "EQUIPAMENTO",
"LAT", "LONG", 
"STATUS",
 "FEBRE", "TOSSE", "DORGARGANTA", "FALTAR", "NAUSEA", "DIARREIA", "ARTRALGIA",
"CEFALEIA", "HEMORRAGIA","EXANTEMA", "CONTATO",
"SERVSAUDE", "CADASTRO", "SINTOMA", "CONTAGEM",
"SIND_DIA","SIND_RES","SIND_EXA", "ATUALIZA");

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
us.id,
us.usuario_id as usuario_id, u.apelido, 
u.idade, u.sexo, 
u.email ,u.idioma, 
us.data_cadastro as data_cadastro,
Date(us.data_cadastro) as data_cadastro_dia,
Time(us.data_cadastro) as data_cadastro_hora,
us.cidade_id as cidade,
us.cidade_regiao_metro as regiao,
u.device,
us.latitude as latitude, us.longitude as longitude, us.sentimento as sentimento,
us.campo1 as campo1, us.campo2 as campo2, us.campo3 as campo3,
us.campo4 as campo4, us.campo5 as campo5, us.campo6 as campo6, 
us.campo7 as campo7, us.campo8 as campo8, us.campo9 as campo9,
us.campo10 as campo10, us.campo11 as campo11, us.campo12 as campo12, 
u.data_hora as data_hora
FROM usuarios u
INNER JOIN usuario_sentimento us ON us.usuario_id = u.id
order by us.usuario_id, us.data_cadastro
limit 0,30";
//limit " . $total_sentimento["total"] . "," . $listagem;

$conn->ExecuteSQL($sql);
$array[0] = $titulo; 
$array[1] = $conn->ArrayResults();

$recife = new DateTimeZone("America/Recife");	
$HoraBrasilia = new DateTime();
$HoraBrasilia->setTimezone($recife);
$Hora = $HoraBrasilia->format("H_i_s");

$arquivo = "saudenacopa.csv";
echo array2csv($array,$conn,$arquivo);
download_send_headers($arquivo);
//download_send_headers("saudenacopa_" . date("Y_m_d") . "_" . $Hora . ".csv");

die();
?>