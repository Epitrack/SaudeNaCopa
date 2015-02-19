<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
session_start();
include "class.MySQL.php";
$conn = new MySQL();

$arquivo = 'planilha_download_saude_na_copa.xls';

try{
		$sql = "SELECT * FROM usuario_sentimento";
		$conn->ExecuteSQL($sql);
		$listagemGeral = $conn->ArrayResults();
		
		$conn->ExecuteSQL("SELECT * FROM sentimentos");
		$sentimentos = $conn->ArrayResults();
		
		
		
}catch (Exception $e){
	echo $e->getMenssage();
}		

// Criamos uma tabela HTML com o formato da planilha
$html = '';
$html .= '<table border=1>';
$html .= '<tr>';
$html .= '<td colspan="4">Download Saude Na Copa</td>';
$html .= "<td colspan='" . count($sentimentos) . "'>Sinais e Sintomas</td>";
$html .= '</tr>';

$contadorMenu=0;
$contadorAssistencia=0;
foreach($listagemGeral as $geral){
	
	$sql = "SELECT u.id, u.apelido, u.idade, u.sexo, u.email , u.data_hora, 
			u.hash, u.gcmid, u.idioma, us.data_cadastro, us.pontuacao,
			us.latitude, us.longitude, c.nome, us.campo1,
			us.campo2, us.campo3, us.campo4, us.campo5, us.campo6,
			us.campo7, us.campo8, us.campo9, us.campo10, us.campo11,
			us.campo12
			from usuarios u
			INNER JOIN usuario_sentimento us ON us.usuario_id = u.id
			INNER JOIN cidade c ON us.cidade_id = c.id";
	$conn->ExecuteSQL($sql);
	$num_rows = $conn->ArrayResults();
	
	if(count($num_rows) >= 1) {
		
	//$html .= '<tr>';
	//$html .= '<td colspan="3">' . $geral["nome_unidade"] . ' - ' . $geral["nome_sub_unidade"] . '</td>';
	//$html .= '</tr>';
	if($contadorMenu < 1){
		
		$html .= '<tr>';
		$html .= '<td><b>ID</b></td>';
		$html .= '<td><b>usuario_id</b></td>';
		$html .= '<td><b>data_cadastro</b></td>';
		$html .= '<td><b>jogo_ok</b></td>';
		$html .= '<td><b>pontuacao</b></td>';
		$html .= '<td><b>latitude</b></td>';
		$html .= '<td><b>longitude</b></td>';
		$html .= '<td><b>cidade_id</b></td>';
		
		foreach($sentimentos as $sentimento){
			$html .= '<td><b>' . $sentimento["nome"] . '</b></td>';
		}
		
		$html .= '<td><b>SEXO</b></td>';
		$html .= '<td><b>IDADES</b></td>';
		$html .= '<td><b>PAIS</b></td>';
		$html .= '<td><b>ESTADO</b></td>';
		$html .= '<td><b>CIDADE</b></td>';
		
		foreach($sinais_sintomas as $listaSS){
			$html .= '<td><b>' . $listaSS["sinais_desc"] . '</b></td>';
		}
		
		foreach($hipotese_diagnosticas as $hipotese_diagnostica){
			$html .= '<td><b>' . $hipotese_diagnostica["hip_dia_desc"] . '</b></td>';
		}
		
		$html .= '<td><b>DATA</b></td>';
		$html .= '<td><b>HORA</b></td>';
		$html .= '<td><b>USUARIO</b></td>';
		$html .= '<td><b>CELULAR</b></td>';
		$html .= '<td><b>LATITUDE</b></td>';
		$html .= '<td><b>LONGITUDE</b></td>';
		$html .= '<td><b>ENCAMINHAMENTO</b></td>';
		$html .= '<td><b>DATA_SERVIDOR</b></td>';
		$html .= '<td><b>HORA_SERVIDOR</b></td>';
		$html .= '<td><b>OBSERVACAO</b></td>';
		
		$html .= '</tr>';
	
	}
	$contadorMenu++;	
	
			foreach($num_rows as $row){
				
				$arrayTipo = array('', 'Expectador','Trabalhador','Outros');
				$publicoTipo = $arrayTipo[ $row["publico_id"] ]; 
				
				$dnciNome = "";
				if($row["dnci_id"] != 0){
					$sqlDnci = "select descricao from suspeita_dnci where id = " . $row["dnci_id"];
					$conn->ExecuteSQL($sqlDnci);
					$dnci = $conn->ArrayResults();
					$dnciNome = $dnci[0]["descricao"];
				}else{
					$dnciNome = "";
				}
				
				$arraySexo = array('','Masculino','Feminino','Ignorado');
				$sexoTipo = $arraySexo[$row["sexo"]]; 
				
				$pais = array();
				if($row["pais"] != ""){
					$sqlPais = "select * from paises where id = " . $row["pais"];
					$conn->ExecuteSQL($sqlPais);
					$pais = $conn->ArrayResult();
					//var_dump($pais);
				}else{ $pais["nome"] = ""; }	
				
				$estado=array();
				if($row["estado"] != "" or $row["estado"] != 0) {
					$slqEstado = "select * from estado where id = " . $row["estado"];
					$retorno = $conn->ExecuteSQL($slqEstado);
					if($conn->iRecords > 0){
						$estado = $conn->ArrayResult();
					}else{
						$estado["nome"] = "";
					}
				}else{ $estado["nome"] = ""; }
				
				$cidade=array();
				if($row["cidade"] != ""){
					$sqlCidade = "select * from cidade where id = " . $row["cidade"];
					$retorno = $conn->ExecuteSQL($sqlCidade);
					if($conn->iRecords > 0){
						$cidade = $conn->ArrayResult();
					}else{
						$cidade["nome"] = "";
					}
				}else{ $cidade["nome"] = ""; }

				//Data e hora que o usuario colocou
				$extrair = explode(" ", $row["data_hora"]);
				$data = explode("-", $extrair[0]);
				$hora = $extrair[1];
				
				//Data e hora que o servidor cadastrou
				$extrairServidor = explode(" ", $row["data_servidor"]);
				$dataServidor = explode("-", $extrairServidor[0]);
				$horaServidor = $extrairServidor[1];
				
				$conn->ExecuteSQL("select username from users where id = " . $row["usuario_id"]);
				$users = $conn->ArrayResult();
				
				$encaminhamentosArray = array("","Alta","Óbito","Transferência");
			
					$html .= '<tr>';
			 		$html .= '<td>' . $row["assistencia_id"] . '</td>';
			 		$html .= '<td>' . $geral["nome_unidade"] . '</td>';		 		
					$html .= '<td>' . $geral["nome_sub_unidade"] . '</td>';
			 		$html .= '<td>' . $publicoTipo . '</td>';
			 		
			 		$sql = "SELECT suspeita_dnci.descricao as nome
							from assistencia_dnci, suspeita_dnci 
							where suspeita_dnci.id = assistencia_dnci.dnci_id 
							and assistencia_dnci.assistencia_id = " . $row["assistencia_id"];
			 		$conn->ExecuteSQL($sql);
					$cadDNCI = $conn->ArrayResults();
			 		
					$html .= (count($cadDNCI) > 0)? '<td><b>1</b></td>' : '<td><b>0</b></td>';
					$html .= '<td><b>' . count($cadDNCI) . '</b></td>';
					
					$achei = false;
					foreach($suspeita_dnci as $dnci){
			 			
			 			foreach($cadDNCI as $listaDnci){
			 				
			 				if($dnci["descricao"] == $listaDnci["nome"]){
								$html .= '<td><b>1</b></td>';
								$achei = true;
			 				}
			 				
			 			}
			 			if(!$achei){
			 				$html .= '<td><b>0</b></td>';
			 			}
			 			$achei = false;
			 		}
			 		
			 		
			 		
			 		$html .= '<td>' . $sexoTipo . '</td>';
			 		$html .= '<td>' . $row["idade"] . '</td>';
			 		
			 		$html .= '<td>' . $pais["nome"] . ' (' . $row["pais"] . ')' . '</td>';
			 		$html .= '<td>' . $estado["nome"] . ' (' . $row["estado"] . ')' . '</td>';
			 		$html .= '<td>' . $cidade["nome"] . ' (' . $row["cidade"] . ')' . '</td>';
			 		
			 		$sql = "SELECT sinais_sintomas.sinais_desc as nome 
								from assistencia_sinais_saude, sinais_sintomas 
								where sinais_sintomas.sinais_id = assistencia_sinais_saude.sinais_id 
								and assistencia_sinais_saude.assistencia_id = " . $row["assistencia_id"];
			 		$conn->ExecuteSQL($sql);
					$cadSinaisSaude = $conn->ArrayResults();
			 		
					$achei = false;
			 		foreach($sinais_sintomas as $listaSS){
			 			
			 			foreach($cadSinaisSaude as $listaSinaisSaude){
			 				
			 				if($listaSinaisSaude["nome"] == $listaSS["sinais_desc"]){
								$html .= '<td><b>1</b></td>';
								$achei = true;
			 				}
			 				
			 			}
			 			if(!$achei){
			 				$html .= '<td><b>0</b></td>';
			 			}
			 			$achei = false;
			 		}

			 		$sql = "SELECT hipotese_diagnostica.hip_dia_desc as nome 
							from assistencia_hipotese_diagnostica, hipotese_diagnostica 
							where hipotese_diagnostica.hip_dia_id = assistencia_hipotese_diagnostica.hip_dia_id 
							and assistencia_hipotese_diagnostica.assistencia_id = " . $row["assistencia_id"];
			 		$conn->ExecuteSQL($sql);
					$cadHipoteseDiagnostica = $conn->ArrayResults();
			 		
					$achei = false;
					foreach($hipotese_diagnosticas as $hipotese_diagnostica){
			 			
			 			foreach($cadHipoteseDiagnostica as $listaHipoteseDiagnostica){
			 				
			 				if($hipotese_diagnostica["hip_dia_desc"] == $listaHipoteseDiagnostica["nome"]){
								$html .= '<td><b>1</b></td>';
								$achei = true;
			 				}
			 				
			 			}
			 			if(!$achei){
			 				$html .= '<td><b>0</b></td>';
			 			}
			 			$achei = false;
			 		}
			 		
			 		$html .= '<td>' . $data[2] . "/" . $data[1] . "/" . $data[0] .  '</td>';
					$html .= '<td>' . $hora . '</td>';
					$html .= '<td>' . $users["username"] . '</td>';
					$html .= '<td>' . $row["celular_id"] . '</td>';
					$html .= '<td>' . $row["latitude"] . '</td>';
					$html .= '<td>' . $row["longitude"] . '</td>';
					$html .= '<td>' . $encaminhamentosArray[$row["encaminhamentos"]] . '</td>';
					$html .= '<td>' . $dataServidor[2] . "/" . $dataServidor[1] . "/" . $dataServidor[0] .  '</td>';
					$html .= '<td>' . $horaServidor . '</td>';
					
					$html .= '<td>' . $row["observacao"] . '</td>';
					$html .= '</tr>';
					
			 }
		}	 

		
		
}//listagem geral			 
			 
	$html .= '</table>';
	
// Configuracoes header para forçar o download

  header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
  header ("Cache-Control: no-cache, must-revalidate");
  header ("Pragma: no-cache");
  header ("Content-type: application/x-msexcel");
  header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
  header ("Content-Description: PHP Generated Data" );

// Envia o conteúdo do arquivo
echo $html;
exit;

?>