<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
@session_start();
function blocoTotalDeCasos($tipo, $resultados) { 
	
	$linhaAbertura = "\n".'<div class="row-fluid">'."\n";
	$divFechamento = '</div>'."\n";
	$quantidadePorLinha = 5;
	$espacamento = '<div class="span1"></div>';
	
	foreach($resultados as $iResultado => $resultado) {
		$iResultado = $iResultado +1;
		$colunaAbertura = "\n\t".'<div id="total_'.$tipo.'_'.$resultado["id"].'"  data-id="'.$resultado["id"].'" data-tipo="'.$tipo.'" class="span2">';

		//Se for o primeiro resultado, abre a linha
		if($iResultado == 1) { 
			echo $linhaAbertura; 
		} 

		if($iResultado >0 && $iResultado%$quantidadePorLinha == 1 ) {
			echo $espacamento;
		}
		
		//gerar Graficos Linhas
		
		if($resultado['nome'] == ""){
			echo  
			$colunaAbertura.
				'<p>Estou bem</p>'.'<span>'.$resultado['total'].'</span>'.
			$divFechamento;
			
		}else{
			
			echo  
				$colunaAbertura.
					'<p>'.utf8_encode($resultado['nome']).'</p>'.'<span>'.$resultado['total'].'</span>'.
				$divFechamento;
				
		}
			

		//Se for o último resultado da linha, fecha a linha
		if($iResultado >0 && $iResultado%$quantidadePorLinha == 0 ) {
			echo $espacamento.$divFechamento.$linhaAbertura;
		}

		//Se for o último resultado fecha a linha
		if($iResultado == count($resultados)) { 
			echo $espacamento.$divFechamento; 
		}
		 
	}
}

function verificarAssistencia($usuario, $request) {
	$db = new MySQL();
	//$db->ExecuteSQL("SELECT count(id) as total FROM questionarios_assistencia WHERE usuario_id = '".$usuario."' AND request_id = '".$request."'");
	$db->ExecuteSQL("SELECT count(assistencia_id) as total FROM assistencia");
	$resultados = $db->ArrayResult();
	return $resultados['total'] == "0" ? FALSE : TRUE;
}


function casosVigilancia($comNome=true) {
	$nomeId = $comNome ? 'id' : 'i';
	$nomeTotal = $comNome ? 'total' : 't';
	$select = $comNome ? "nome, tabela as '$nomeId'" : "tabela as '$nomeId'";
	$db = new MySQL();
	$db->ExecuteSQL("SELECT $select, query from tabelas_questionarios q WHERE q.tabela <> 'surto'");
	$tabelasVigilancia = $db->ArrayResults();
	$resultado = array();
	
	foreach($tabelasVigilancia as $tabelaVigilancia) {

		$tabela = $tabelaVigilancia[$nomeId];
		
		$query = $tabelaVigilancia["query"];

		$sqlQuery = "SELECT count(id) as total from $tabela $query";
		$db->ExecuteSQL($sqlQuery);
		$total = $tabelasVigilancia = $db->ArrayResult();

		$tabelaVigilancia[$nomeTotal] = $total['total'];

		array_push($resultado, $tabelaVigilancia);
	} 
	return $resultado;
}


function casosSurto($comNome=true) {
	$nomeId = $comNome ? 'id' : 'i';
	$nomeTotal = $comNome ? 'total' : 't';
	$select = $comNome ? "nome, tabela as '$nomeId'" : "tabela as '$nomeId'";
	$db = new MySQL();
	$selectFinal = "SELECT $select from tabelas_questionarios q WHERE q.tabela = 'surto'";
	$db->ExecuteSQL($selectFinal);
	$tabelasVigilancia = $db->ArrayResults();
	$resultado = array();
	
	foreach($tabelasVigilancia as $tabelaVigilancia) {

		$tabela = $tabelaVigilancia[$nomeId];

		$db->ExecuteSQL("SELECT count(id) as total 
						 from $tabela q 
						 where q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
						 and q.ativo = 1");
		$total = $tabelasVigilancia = $db->ArrayResult();

		$tabelaVigilancia[$nomeTotal] = $total['total'];

		array_push($resultado, $tabelaVigilancia);
	} 
	return $resultado;
}

function totalLocalExposicao(){
		$db = new MySQL();
		$sql = "SELECT local_exposicao_id as i, count(local_exposicao_id) as t
				FROM surto_local_exposicao, surto
				WHERE surto.id = surto_local_exposicao.surto_id
				AND surto.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				AND surto.ativo = 1
				GROUP BY local_exposicao_id";
		$db->ExecuteSQL($sql);
		$query = $db->ArrayResults();
		$localExposicao= array();
		$final = array();
		foreach($query as $lista){
			$localExposicao["i"] = $lista["i"];
			$localExposicao["t"] = $lista["t"];
			array_push($final, $localExposicao);
		}
		return $final;
}

function totalFonteContaminacao(){
	$db = new MySQL();
	$sql = "SELECT fonte_contaminacao_id as i, count(fonte_contaminacao_id) as t  
			FROM surto_fonte_contaminacao, surto
				WHERE surto.id = surto_fonte_contaminacao.surto_id
				AND surto.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				AND surto.ativo = 1
			GROUP BY fonte_contaminacao_id";
	$db->ExecuteSQL($sql);
	$query = $db->ArrayResults();
	$fonteContaminacao= array();
	$final = array();
	$contador=4;
	foreach($query as $lista){
		$fonteContaminacao["i"] = $lista["i"];
		$fonteContaminacao["t"] = $lista["t"];
		array_push($final, $fonteContaminacao);
		$contador++;
	}
	return $final;
}

function totalNumeroEnvolvidos(){
	$db = new MySQL();
	$sql = "SELECT q.envolvidos as t 
			FROM surto q
			WHERE q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
			AND q.ativo = 1
			GROUP BY q.envolvidos";
	$db->ExecuteSQL($sql);
	$query = $db->ArrayResults();
	$numeroEnvolvidos= array("i","t");
	foreach($query as $lista){
		$numeroEnvolvidos["i"] = 11;
		$numeroEnvolvidos["t"] += $lista["t"];
	}
	return $numeroEnvolvidos;
}

function totalHoraSurtos(){
	
	$db = new MySQL();
	$sql = "SELECT HOUR(data_hora) AS x, COUNT(*) AS 'y' 
			FROM surto 
			WHERE data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
			and ativo = 1 group by hour(data_hora)";
	$db->ExecuteSQL($sql);
	return $db->ArrayResults();
	
}

function totalPaisesEstadosCadastros(){
	
	$db = new MySQL();
	$retorno = array();
	//PAISES sem o brasil
	$sql = "select count(pais) as p, pais from assistencia where pais not in (1) group by pais";
	$db->ExecuteSQL($sql);
	$paises = $db->ArrayResults();
	$totalPaises = 0;
	foreach($paises as $listagem) {	
		$totalPaises += $listagem['p'];
	}
	
	//ESTADO
	$sql = "select count(estado) as e, estado from assistencia group by estado";
	$db->ExecuteSQL($sql);
	$estados = $db->ArrayResults();
	$pernambuco = 0;
	$total_estado = 0;
	foreach($estados as $listagem) {
		
		if($listagem["estado"] == 16){
			$pernambuco += $listagem["e"];
		}else{
			$total_estado += $listagem["e"];
		}
	}
	
	//pernabuco
	$retorno[0]["y"] = $pernambuco;
	$retorno[0]["label"] = array("Pernambuco");
	
	$retorno[1]["y"] = $total_estado;
	$retorno[1]["label"] = array("Outros Estados");
	
	$retorno[2]["y"] = $totalPaises;
	$retorno[2]["label"] = array("Outros Paises");
	
	return $retorno; 
}

/* CESAR
function casoSurto(){
	
	$db = new MySQL();
	$db->ExecuteSQL("select * from surto");
	$tabelasSurtos = $db->ArrayResults();
	$resultado = array();
	$total = 1;
	foreach($tabelasSurtos as $tabelaSurto) {

		$resultado["total"] = $total++;
		$resultado["surto"][] = $tabelaSurto;
		
	} 
	return $resultado;
}
*/

function trataValores($arrayValores, $cerquilha = true){
	
	$in="";
	if(!$cerquilha){
		
		foreach($arrayValores as $array){
			if(isset($array["nome"]) !=""){
				$in .= $array["nome"] . ", ";
			}else{
				$in .= $array["descricao"] . ", ";
			}
		}
		$in=substr($in, 0,-2);
	}else{
		$arrayLimpo = explode("#", $arrayValores);
		foreach($arrayLimpo as $array){
				$in .= $array . ","; 	
		}	
		$in=substr($in, 0,-2);
	}
	return $in; 
}

function getSubUnidade($conexao, $id){
	
	if($id != 0){
		$sql = "select nome from sub_unidades where id = " . $id;
		$conexao->ExecuteSQL($sql);
		$query = $conexao->ArrayResult();
		return $subUnidade = $query["nome"];
	}	
	
}

function getUnidade($conexao, $id){
	
	if($id!=0){
		$sql = "select nome from unidades where id = " . $id;
		$conexao->ExecuteSQL($sql);
		$query = $conexao->ArrayResult();
		return $subUnidade = $query["nome"];
	}
}

function grafico($secao, $numero) {
	$retorno = array();
	if($secao == "assistencia") {
		$retorno = graficoAssistencia($numero);
	}
	if($secao == "vigilancia") {
		$retorno = graficoVigilancia($numero);
	}
	return $retorno;
}

function graficoAssistencia($numero) {
	$db = new MySQL();
	switch ($numero) {
		case '1':
			$query = CONSULTA_ATENDIMENTOS_HORA_EM_HORA;
		break;
		case '2':
			$query = CONSULTA_ATENDIMENTOS_HORA_EM_HORA;
			$query = "SELECT COUNT( assistencia_id ) AS y, encaminhamentos AS x, 
			IF( encaminhamentos =1,  'Alta', IF( encaminhamentos =2,'Obito','Transferencia') ) AS legendText 
			FROM assistencia 
			WHERE data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
			and ativo = 1
			GROUP BY encaminhamentos";
		break;
		case '3':
			$query = "select count(idade) as y, idade as x 
					  from assistencia
					  WHERE data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
					  and ativo = 1 
					  group by idade";
		break;
		case '4':
			$query = "select count(local_id) as y, sub_unidades.nome as label 
						from assistencia 
						left join sub_unidades on sub_unidades.id = assistencia.local_id
						where assistencia.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59' 
						and assistencia.ativo = 1
						group by local_id";
		break;
		
		default:
			$query = CONSULTA_ATENDIMENTOS_HORA_EM_HORA;
		break;
	}

	$db->ExecuteSQL($query);
	$resultados = $db->ArrayResults();

	return $resultados;
}

function graficoVigilancia($numero) {
	$db = new MySQL();
	
	switch ($numero) {
		case '1':
			$query = CONSULTA_ATENDIMENTOS_VIGILANCIA_HORA_EM_HORA;
		break;
		case '2':
			//MEDIDAS APLICADAS
			$query = "select ivs.nome as label, count(vs.id) as y, 
					ROUND(((count(vs.id) * 100)/t.contagem),2) as percent
					from vigilancia_sanitaria vs, vigilancia_sanitaria_medida_aplicadas vsi, medidas_aplicadas ivs,
					(select count(medida_aplicadas_id) as contagem from vigilancia_sanitaria_medida_aplicadas) t 
					where vs.locais_id IN ( 1, 2, 3, 4 )
					and vs.id = vsi.vigilancia_sanitaria_id
					and ivs.id = vsi.medida_aplicadas_id
					and vs.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
					and vs.ativo = 1
					GROUP by ivs.nome ";
		break;
		case '3':
			//PRODUTOS
			$query = "SELECT e.nome as label,
					 q.vigilancia_sanitaria as rel, 
					count(q.vigilancia_sanitaria) as y, 
					ROUND(((count(q.vigilancia_sanitaria) * 100)/t.contagem),2) as percent 
					FROM (vigilancia_sanitaria q, 
						(SELECT count(q.id) as contagem 
						FROM vigilancia_sanitaria q where locais_id = 1) t) 
							LEFT JOIN produtos_vigilancia_sanitaria e ON e.id = q.vigilancia_sanitaria
					where q.locais_id IN ( 1, 2, 3, 4 )
					and q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
					and q.ativo = 1		 
					GROUP BY q.vigilancia_sanitaria";
			
		break;
		case '4':
			//INFRACAO
			$query = "select
					vsi.infracao_id as x, 
					ivs.nome as label, count(vs.id) as y, 
					ROUND(((count(vs.id) * 100)/t.contagem),2) as percent
					from vigilancia_sanitaria vs, vigilancia_sanitaria_infracao vsi, infracao_vigilancia_sanitaria ivs,
					(select count(vigilancia_sanitaria_id) as contagem from vigilancia_sanitaria_infracao) t 
					where vs.locais_id IN ( 1, 2, 3, 4 ) 
					and vs.id = vsi.vigilancia_sanitaria_id
					and ivs.id = vsi.infracao_id
					and vs.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
					and vs.ativo = 1
					GROUP by ivs.nome";
		break;
		
		default:
			$query = CONSULTA_ATENDIMENTOS_VIGILANCIA_HORA_EM_HORA;
		break;
	}

	$db->ExecuteSQL($query);
	$resultados = $db->ArrayResults();

	return $resultados;
}


?>