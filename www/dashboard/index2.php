<?php 
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
	include("bibliotecas/php/class.MySQL.php");
	include("bibliotecas/php/class.Auth.php");
	include("bibliotecas/php/inc.consultas.php");
	include("bibliotecas/php/inc.funcoes.php");

	$auth = new Auth();
	$auth->notLogged("login.php");

	$db = new MySQL();
	$db->ExecuteSQL(CONSULTA_ATENDIMENTOS);
	
	$atendimentos = $db->ArrayResults();
	$vigilancias = casosVigilancia();
	$surto = casoSurto();

	$total_atendimentos = 0;
	foreach($atendimentos as $atendimento) {	
		$total_atendimentos += $atendimento['total'];
	}

	$total_vigilancias = 0;
	foreach($vigilancias as $vigilancia) {	
		$total_vigilancias += $vigilancia['total'];
	}
	
	
	
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Venice</title>
	<link rel="stylesheet" href="bibliotecas/bootstrap/css/bootstrap.min.css">	
	<link href="css/style.css" rel="stylesheet" media="screen">
</head>
<body>

	<div id="barraDoAplicativo" class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<a href="#" class="brand">Venice</a>
			<ul class="nav pull-right">
		      <li class="logout active"><a href="logout.php"><i class="icon-off icon-white"></i> Sair</a></li>
		    </ul>
			<ul class="nav">
		      <li class="active dashboard"><a href="index.php">Dashboard</a></li>
		    </ul>
		    <ul class="nav">
		      <li class="active"><a href="cad_assistencia.php">Cadastro Assistência</a></li>
		      <li class="active"><a href="cad_vigilancia.php">Cadastro Vigilância</a></li>
		      <li class="active"><a href="cad_surto.php">Cadastro Surtos</a></li>
		    </ul>
		</div>
	</div>

	<!-- Seção Dashboard -->
	<div id="secao-dashboard" class="container-fluid secao">
		<div id="barraDeCarregamento"></div>

		
		<div id="geralAssistencia" class="row-fluid">
			<!-- Assistência -->
			<div class="span12">

				<!-- Título -->
				<div class="page-header">
				  <h1><a href="bibliotecas/php/download_assistencia.php" title="Download planilha bruta">Assistência</a> <small><?php echo $total_atendimentos; ?></small></h1>
				</div>

				<!-- Conteúdo -->
				<div class="listaDeTotal">
					<canvas id="line" height="300" width="400"></canvas>
					<canvas id="pie" height="300" width="400"></canvas>
					<canvas id="bar" height="200" width="400"></canvas>
					<?php
						blocoTotalDeCasos("assistencia", $atendimentos);
						require 'bibliotecas/php/phplot/graficos.php';
					?>
					</div>
				</div>
			</div>
		</div>

		

		<!-- Vigilância -->
		<div id="geralVigilancia" class="row-fluid">
			<div class="span12">

				<!-- Título -->
				<div class="page-header">
				  <h1><a href="bibliotecas/php/download_vigilancia.php" title="Download planilha bruta">Vigilância</a> <small><?php echo $total_vigilancias; ?></small></h1>
				</div>

				<!-- Conteúdo -->
				<div class="listaDeTotal">
					<?php
						blocoTotalDeCasos("vigilancia", $vigilancias);
					?>
				</div>
			</div>
		</div>
	</div>

	<!-- SURTO -->
		<div id="geralSurto" class="row-fluid">
			<div class="span12">

				<!-- Título -->
				<div class="page-header">
				  <h1><a href="bibliotecas/php/download_surto.php" title="Download planilha bruta">Surto</a> <small><?php echo $surto["total"]; ?></small></h1>
				</div>

				<!-- Conteúdo -->
				<div class="listaDeTotal">
					
				</div>
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
			"bibliotecas/js/jquery-1.9.0.min.js", 
			"bibliotecas/bootstrap/js/bootstrap.min.js", 
			"bibliotecas/js/APP.js",
			"js/APP.Dashboard.js",
			"js/Chart.js"
		]);
	</script>
</body>
</html>