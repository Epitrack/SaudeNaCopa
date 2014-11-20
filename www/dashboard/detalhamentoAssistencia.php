<?php 
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
	include("bibliotecas/php/class.MySQL.php");
	include("bibliotecas/php/class.Auth.php");;
	include("bibliotecas/php/class.Entidade.php");

	$id = $_GET['id'];
	$dt = new SubUnidade($id);

	$auth = new Auth();
	$auth->notLogged("login.php");
?>


<?php if(isset($_GET['async']) && $_GET['async'] == "false") { ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Amber</title>
	<link rel="stylesheet" href="bibliotecas/bootstrap/css/bootstrap.min.css">	
	<link href="css/style.css" rel="stylesheet" media="screen">
</head>
<body>


	<div id="barraDoAplicativo" class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<a href="#" class="brand">Amber</a>
			<ul class="nav pull-right">
		      <li class="logout active"><a href="logout.php"><i class="icon-off icon-white"></i> Sair</a></li>
		    </ul>
			<ul class="nav">
		      <li class="dashboard"><a href="index.php">Dashboard</a></li>
		      <li class="active"><a href="#">Detalhamento</a></li>
		    </ul>
		</div>
	</div>


	<!-- Seção Dashboard -->
	<div id="secao-dashboard" class="container-fluid secao">
		<div id="barraDeCarregamento"></div>

		
		<div id="geralAssistencia" class="row-fluid">
			<!-- Assistência -->
			<div class="span12">
				<!-- Seção Dashboard -->
					

<?php } echo $dt->html(); if(isset($_GET['async']) && $_GET['async'] == "false") { ?> 
	</div></div></div></body></html>
<?php } ?>