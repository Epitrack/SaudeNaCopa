<?php 
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
	include("bibliotecas/php/class.MySQL.php");
	include("bibliotecas/php/inc.consultas.php");

	include("bibliotecas/php/inc.funcoes.php");

	$db = new MySQL();
	$dados = array();
	
	$db->ExecuteSQL(CONSULTA_ATENDIMENTOS_PERIODICA);
	$total_assistencia = $db->ArrayResults();
	
	$db->ExecuteSQL(CONSULTA_VIGILANCIA_PERIODICA);
	$total_vigilancia = $db->ArrayResults();
	
//ASSISTENCIA E QUANTITATIVOS POR UNIDADE
//=====================================================================================================
	$dados['graficoAssistencia1'] = grafico("assistencia", 1);
	if(count($dados['graficoAssistencia1']) <= 0){
		$dados['graficoAssistencia1'] = array(array("x"=>0,"y"=>0));
	}
	
	//$dados['graficoAssistencia2'] = grafico("assistencia", 2);
	$colocaAspas = array();
	$contadorArray=0;
	foreach(grafico("assistencia", 2) as $listaGraficoAssistencia){
		$colocaAspas[$contadorArray]["y"] = $listaGraficoAssistencia["y"];
		$colocaAspas[$contadorArray]["x"] = $contadorArray + 1;
		$colocaAspas[$contadorArray]["legendText"] = array(utf8_encode($listaGraficoAssistencia["legendText"]));
		$contadorArray++; 
	}
	if(count($colocaAspas) >= 1){
		$dados['graficoAssistencia2'] = $colocaAspas;
	}else{
		$dados['graficoAssistencia2'] = array(array("x"=>0,"y"=>0,"legendText"=>array()));
	}
	
	$dados['graficoAssistencia3'] = grafico("assistencia", 3);
	if(count($dados['graficoAssistencia3']) <= 0){
		$dados['graficoAssistencia3'] = array(array("x"=>0,"y"=>0));
	}
	
	$colocaAspas = array();
	$contadorArray=0;
	foreach(grafico("assistencia", 4) as $listaGraficoAssistencia){
		$colocaAspas[$contadorArray]["y"] = $listaGraficoAssistencia["y"];
		$colocaAspas[$contadorArray]["label"] = array(utf8_encode($listaGraficoAssistencia["label"]));
		$contadorArray++; 
	}
	if(count($colocaAspas) >= 1){
		$dados['graficoAssistencia4'] = $colocaAspas;
	}else{ 
		$dados['graficoAssistencia4'] = array(array("y"=>0,"label"=>array()));
	}			
	
	$dados['assistencia'] = $total_assistencia;
	//$dados['vigilancia'] = casosVigilancia(false);
	$dados['vigilancia'] = $total_vigilancia; 
	$dados['surtos'] = casosSurto(false);
	
	

//GRAFICOS/DADOS ABA VIGILANCIA
//=====================================================================================================	
	
	$dados['graficoVigilancia1'] = grafico("vigilancia", 1);
	if(count($dados['graficoVigilancia1']) <= 1){ 
		$dados['graficoVigilancia1'] = array(array("y"=>0,"label"=>array()));
	}
	//------------------------------------------
	
	$colocaAspas = array();
	$contadorArray=0;
	foreach(grafico("vigilancia", 2) as $listaGraficoVigilancia){
		$colocaAspas[0][0]["total"] = $contadorArray;
		
		$colocaAspas[0][$contadorArray]["y"] = $listaGraficoVigilancia["y"];
		$colocaAspas[0][$contadorArray]["label"] = array(utf8_encode($listaGraficoVigilancia["label"]));
		
		$colocaAspas[1][$contadorArray]["y"] = $listaGraficoVigilancia["percent"];
		$colocaAspas[1][$contadorArray]["label"] = array(utf8_encode($listaGraficoVigilancia["label"]));
		
		$contadorArray++;
	}
	if(count($colocaAspas) >= 1){
		$dados['graficoVigilancia2'] = $colocaAspas;
	}else{
		$dados['graficoVigilancia2'] = array(array("y"=>0,"label"=>array()), array("y"=>0,"label"=>array()));
	}
	
	
	//------------------------------------------
	
	$colocaAspas = array();
	$contadorArray=0;
	foreach(grafico("vigilancia", 3) as $listaGraficoVigilancia){
		$colocaAspas[0][0]["total"] = $contadorArray;
		
		$colocaAspas[0][$contadorArray]["y"] = $listaGraficoVigilancia["y"];
		$colocaAspas[0][$contadorArray]["label"] = array(utf8_encode($listaGraficoVigilancia["label"]));
		$colocaAspas[0][$contadorArray]["rel"] = $listaGraficoVigilancia["rel"];
		
		$colocaAspas[1][$contadorArray]["y"] = $listaGraficoVigilancia["percent"];
		$colocaAspas[1][$contadorArray]["label"] = array(utf8_encode($listaGraficoVigilancia["label"]));
		
		$contadorArray++;
	}
	if(count($colocaAspas) >= 1){
		$dados['graficoVigilancia3'] = $colocaAspas;
	}else{
		$dados['graficoVigilancia3'] = array(array("y"=>0,"label"=>array()));
	}
	//------------------------------------------
	
	$colocaAspas = array();
	$contadorArray=0;
	foreach(grafico("vigilancia", 4) as $listaGraficoVigilancia){
		$colocaAspas[0][0]["total"] = $contadorArray;
		
		$colocaAspas[0][$contadorArray]["x"] = $listaGraficoVigilancia["x"];
		$colocaAspas[0][$contadorArray]["y"] = $listaGraficoVigilancia["y"];
		$colocaAspas[0][$contadorArray]["label"] = array(utf8_encode($listaGraficoVigilancia["label"]));
		
		$colocaAspas[1][$contadorArray]["y"] = $listaGraficoVigilancia["percent"];
		$colocaAspas[1][$contadorArray]["label"] = array(utf8_encode($listaGraficoVigilancia["label"]));
		
		$contadorArray++;
		
	}
	if(count($colocaAspas) >= 1){
		$dados['graficoVigilancia4'] = $colocaAspas;
	}else{
		$dados['graficoVigilancia4'] = array(array("y"=>0,"label"=>array()));
	}

//GRAFICOS/DADOS ABA SURTO
//=====================================================================================================
	$dados['totalLocalExposicao'] = totalLocalExposicao();
	$dados['totalFonteContaminacao'] = totalFonteContaminacao();
	$dados['totalPessoasEnvolvidos'] = totalNumeroEnvolvidos();
	
	$dados['totalHoraSurtos'] = totalHoraSurtos();
	
	
	
//GRAFICOS/DADOS ABA INFORMACOES GERAIS
//=====================================================================================================
	//GRAFICO 1
	$totalHoras = 0;
	$juntarHorasTodos = array();
	$contadorListaGrafico = 0;
	
	foreach($dados['graficoAssistencia1'] as $lista){

		$juntarHorasTodos[$contadorListaGrafico]["x"] = round($lista["x"]);
		$juntarHorasTodos[$contadorListaGrafico]["y"] = round($lista["y"]);
		
		foreach($dados['graficoVigilancia1'] as $listaVigilancia){
			if($lista["x"] ==  $listaVigilancia["x"]){
				$juntarHorasTodos[$contadorListaGrafico]["x"] = round($listaVigilancia["x"]);
				$juntarHorasTodos[$contadorListaGrafico]["y"] += round($listaVigilancia["y"]);
			}
		}
		foreach($dados['totalHoraSurtos'] as $listaSurto){
			if($lista["x"] ==  $listaSurto["x"]){
					$juntarHorasTodos[$contadorListaGrafico]["x"] = round($listaSurto["x"]);
					$juntarHorasTodos[$contadorListaGrafico]["y"] += round($listaSurto["y"]);
				}
			}
		$contadorListaGrafico++;
	}
	
	$dados['dados_grafico_1_geral'] = $juntarHorasTodos;
	
	//GRAFICO 2
	
	$total_atendimentos = 0;
	foreach($total_assistencia as $atendimento) {	
		$total_atendimentos += $atendimento['t'];
	}
	
	$total_vigilancias = 0;
	foreach($total_vigilancia as $vigilancia) {	
		$total_vigilancias += $vigilancia['t'];
	}
	$total_surto = $dados['surtos'][0]['t'];
	$total = $total_atendimentos + $total_vigilancias + $total_surto;
	$geralGrafico2 = array();
	$geralGrafico2[0][0]['y'] = round($total_atendimentos);
	$geralGrafico2[0][0]['label'] = array("Assistencia");
	
	$geralGrafico2[1][0]['y'] = round($total_atendimentos * 100 / $total);
	$geralGrafico2[1][0]['label'] = array("Assistencia");
	
	$geralGrafico2[0][1]['y'] = round($total_vigilancias);
	$geralGrafico2[0][1]['label'] = array("Vigilancia");
	
	$geralGrafico2[1][1]['y'] = round($total_vigilancias * 100 / $total);
	$geralGrafico2[1][1]['label'] = array("Vigilancia");
	
	$geralGrafico2[0][2]['y'] = round($total_surto);
	$geralGrafico2[0][2]['label'] = array("Surto");
	
	$geralGrafico2[1][2]['y'] = round($total_surto * 100 / $total);
	$geralGrafico2[1][2]['label'] = array("Surto");
	
	$dados['dados_grafico_2_geral'] = $geralGrafico2;
	
	//GRAFICO 3
	$dados['dados_grafico_3_geral'] = totalPaisesEstadosCadastros();
	
	//GRAFICO 4
	
	//GRAFICO 5
	
//MARAVILHO JSON
//=====================================================================================================	
	header('content-type: application/json; charset=utf-8'); 
	echo json_encode($dados);
	
?>