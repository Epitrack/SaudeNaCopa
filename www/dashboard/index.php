<?php 
/**
 * Amber
 * *
 *  Copyright (C) 2013 - César Martins - Todos os direitos reservados 
 *
 *  Este programa não pode ser copiado, modificado e nem distribuido. 
 *  Essa versão é licenciada apenas para a secretária de saúde do estado de Pernambuco. 
 *  
 *  
 * @package principal
 * @author César Martins
 * @version 1.0
 */
session_start();

	include("bibliotecas/php/class.MySQL.php");
	include("bibliotecas/php/class.Auth.php");
	include("bibliotecas/php/inc.consultas.php");
	include("bibliotecas/php/inc.funcoes.php");
	include("bibliotecas/php/class.Entidade.php");

	$auth = new Auth();
	$auth->notLogged("login.php");

	$db = new MySQL();
	
		$data_inicio = date("d/m/Y");
		$data_final = date("d/m/Y");
		$validar=0;
		$auth->setSession(date("d/m/Y"), date("d/m/Y"), 0, false);
	
	//Geral de totais
	
	$db->ExecuteSQL("select count(us.sentimento_id) as total, s.nome, s.id
					from usuario_sentimento us
					left join sentimentos s on us.sentimento_id = s.id
					group by sentimento_id");
	$atendimentos = $db->ArrayResults();	
		
	$db->ExecuteSQL("select count(us.sentimento_id) as total
					from usuario_sentimento us where us.sentimento_id = 0");
	$vigilancias = $db->ArrayResults();
	
	
	$total_atendimentos = 0;
	foreach($atendimentos as $atendimento) {	
		$total_atendimentos += $atendimento['total'];
	}

	$total_vigilancias = 0;
	foreach($vigilancias as $vigilancia) {	
		$total_vigilancia += $vigilancia['total'];
	}

	$total_surtos = 0;
	//foreach($surtos as $surto) {	
	//	$total_surtos += $surto['total'];
	//}
	
	$total_surtos_local_exposicao = 0;
	//foreach($oS->provavelLocal as $lista){
	//	$total_surtos_local_exposicao += $lista["qtd"];		
	//}
	 
	$total_surtos_provavel_contaminacao = 0;
	//foreach($oS->provavelContaminacao as $lista){
	//	$total_surtos_provavel_contaminacao += $lista["qtd"];
	//}
	
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Saúde na copa</title>
	<link rel="stylesheet" href="bibliotecas/bootstrap/css/bootstrap.min.css">	
	<link rel="stylesheet" href="bibliotecas/bootstrap/css/bootstrap-datetimepicker.min.css">
	<link href="css/style.css" rel="stylesheet" media="screen">
	<link href="css/APP.Dashboard.css" rel="stylesheet" media="screen">
	<link href="css/APP.Dashboard.Grafico.css" rel="stylesheet" media="screen">
	
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/tags/markermanager/1.0/src/markermanager.js"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer_compiled.js"></script>
	
</head>
<body>

	<div id="barraDoAplicativo" class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<a href="#" class="brand">Saúde na copa</a>
			<ul class="nav pull-right">
		      <li class="logout active"><a href="logout.php"><i class="icon-off icon-white"></i> Sair</a></li>
		    </ul>
			<ul class="nav">
		      <li class="active dashboard"><a href="index.php">Dashboard</a></li>
		    </ul>
		    <?php include 'menu.php'; ?>
		</div>
	</div>

	<!-- Seção Dashboard -->
	<div id="secao-dashboard" class="container-fluid secao">
		

		<ul class="nav nav-tabs menu-paginas">
			<li class="active" data-pagina="geral"><a href="#">Informações Gerais</a></li>
			<li data-pagina="quantitativos"><a href="#">Quantitativos por Sentimento</a></li>
		</ul>

		<div class="pagina ativa" data-pagina="geral">
			<div class="row-fluid">
				<div class="span8">
					
					<div class="row-fluid">
						<div id="graficoGeral6" class="span12 grafico">
							<iframe width="100%" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://localhost/amber/maps/index.php?assistencia=1&?vigilancia=1"></iframe><br />
						</div>
					</div>
				</div>
			</div>

		</div>
		
		<div class="pagina" data-pagina="quantitativos">
		
			<div id="barraDeCarregamento"></div>
			
			<div id="geralAssistencia" class="row-fluid">
				<!-- Assistência -->
				<div class="span12">

					<!-- Título -->
					<div class="page-header">
					  <h1><a href="bibliotecas/php/download_assistencia.php" title="Download planilha bruta">Total</a> <small><?php echo $total_atendimentos; ?></small></h1>
					</div>

					<!-- Conteúdo -->
					<div class="listaDeTotal">
						<?php
							blocoTotalDeCasos("assistencia", $atendimentos);
						?>
					</div>
				</div>
			</div>

			<!-- Vigilância -->
			<div id="geralVigilancia" class="row-fluid">
				<div class="span12">

					<!-- Título -->
					<div class="page-header">
					  <h1><a href="bibliotecas/php/download_vigilancia.php" title="Download planilha bruta">Estou bem</a> <small><?php echo $total_vigilancias; ?></small></h1>
					</div>

					<!-- Conteúdo -->
					<div class="listaDeTotal">
						<?php blocoTotalDeCasos("vigilancia", $vigilancias); ?>
					</div>
				</div>
			</div>

		</div>

	</div>

<!-- Modal  -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Novo Período</h3>
  </div>
  <div class="modal-body">
    <form action="set_session.php" method="post" onsubmit="return validaDatasPeriodo()"> 
		 <div class="alert fade in" style="display:none;">
            <button type="button" class="close" onclick='$(".alert").hide()'>&times;</button>
            <strong>Ops! </strong><span></span>
          </div>
			
			<span>01 - Data Inicio</span>
			<div class="well">
				<div id="datetimepicker4" class="input-append date">
				    <input id="campoDataHoraInicio" data-format="dd/MM/yyyy" type="text" name="data_inicio" maxlength="19" value="<?php echo $data_inicio?>"></input>
				    <span class="add-on">
				      <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
				    </span>
				  </div>
			</div>
			
			<span>02 - Data Final</span>
			<div class="well">
				<div id="datetimepicker5" class="input-append date">
				    <input id="campoDataHoraFinal" data-format="dd/MM/yyyy" type="text" name="data_final" maxlength="19" value="<?php echo $data_final?>"></input>
				    <span class="add-on">
				      <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
				    </span>
				  </div>
			</div>
			
			<div class="well">
				<div>
				    <input id="marcar_padrao" type="checkbox" name="marcar_padrao" <?php echo ($validar==1)? "checked" : "";?> ></input>
				    Marcar como padrão
				</div>
			</div>
	
  </div>
  <div class="modal-footer">
    <button id="fecharModal" class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
    <button type="submit" class="btn btn-primary">Salvar</button>
  </div>
  </form>
</div>

<div id="myModalError" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel">Atenção</h3>
	</div>
	<div class="modal-body">
		<div class="alert alert-error">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		  <p><strong>Intervalos de datas sem dados relevantes</strong> 
		  	<ul>
		  		<li>Alguns gráficos podem não aparecer ou aparecer com erro</li>
		  	</ul>
		  	Clique em "continuar" para continuar com a data atual ou informe novas datas clicando em "Alterar Datas".
		  </p>
		</div>
		<div class="modal-footer">
		   <button id="fecharModal" class="btn" data-dismiss="modal" aria-hidden="true">Continuar</button>
		   <button data-dismiss="modal" aria-hidden="true" onclick="ativaModal()" class="btn btn-primary">Alterar Datas</button>
		</div>
	</div>	
</div>	



<div id="detalhamento" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Detalhamento</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn ampliar">Ampliar</a>
		<a href="#" class="btn btn-primary fecharModal">Fechar</a>
	</div>
</div>
	<!-- Seção Dashboard -->

	
	<script src="bibliotecas/js/head.min.js"></script>
	<script>

		head.ready(function() {
		   APP.iniciar();
			
		});

		head.js.apply(head,[
			//"http://maps.google.com/maps/api/js?sensor=true", 
			"bibliotecas/js/jquery-1.9.0.min.js", 
			//"bibliotecas/js/jquery.ui.map.full.min.js", 
			//"bibliotecas/js/Chart.js", 
			"bibliotecas/js/canvasjs/canvasjs.min.js", 
			"bibliotecas/bootstrap/js/bootstrap.min.js", 
			"bibliotecas/js/APP.js",
			"js/APP.Dashboard.js",
			"js/APP.Dashboard.Assistencia.js",
			"js/APP.Dashboard.Vigilancia.js",
			"js/APP.Dashboard.Geral.js",
			"bibliotecas/js/bootstrap-datetimepicker.min.js"
		]);


		function ativaModalSemDados(){
			$('#myModalError').modal('show');

		}
		
		function ativaModal(){
			$('#myModal').modal('show');
		}

		function validaDatasPeriodo(){

			var data_inicio = $("#campoDataHoraInicio").val();
			var data_fim = $("#campoDataHoraFinal").val();

			if(data_inicio != "" && data_fim != ""){

				var compara1 = parseInt(data_inicio.split("/")[2].toString() + data_inicio.split("/")[1].toString() + data_inicio.split("/")[0].toString());
				var compara2 = parseInt(data_fim.split("/")[2].toString() + data_fim.split("/")[1].toString() + data_fim.split("/")[0].toString());
				 
				if (compara1 <= compara2){
					return true;
				}else{
					$(".alert").show();
					$(".alert span").html("Data Final menor que a Inicial");
					return false;
				}
									
			}else{
				$(".alert").show();
				$(".alert span").html(" Favor informar o intervalo de datas");
				return false;
			}
			
		}
		
	</script>
	
</body>
</html>