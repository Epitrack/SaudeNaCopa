<?php 
/**
 * Saúde na Copa
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include("php/class.Auth.php");
	$auth = new Auth();
	$mostrar = false;
	if(isset($_POST['login']) && isset($_POST['senha'])) {
		$login = $_POST['login'];
 		$senha = $_POST['senha'];
 		$usuarioAutenticado = $auth->validaLogin();
		if($usuarioAutenticado) {
			header("Location: index.php");
		}else{
			$mostrar = true; 	
		}
	} else {
		$auth->logged("index.php");
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sa&uacute;de na Copa</title>
	<link rel="stylesheet" href="bibliotecas/bootstrap/css/bootstrap.min.css">	
	<link href="css/style.css" rel="stylesheet" media="screen">
</head>
<body>


	<!-- Seção Dashboard -->
	<div id="secao-dashboard" class="container-fluid secao">


		<div id="geralAssistencia" class="row-fluid">	
			<div class="hero-unit span8 offset2">
				<h1>Sa&uacute;de na Copa</h1>
				<p>Para acessar o sistema Sa&uacute;de na Copa você precisa autenticar-se.</p>
				<div id="error" class="alert alert-error" style="display: none;">
					<button type="button" class="close" data-dismiss="error" aria-hidden="true" onclick="APP.apagarErro()">×</button>
					<?php 
						if(count($auth->errormsg) > 1){
							echo "<ul>";
							foreach($auth->errormsg as $listaErros){
								echo "<li>$listaErros</li>";
							}
							echo "</ul>";
						}
					?>
				</div>
				<form method="post" action="login.php">
					<fieldset>
						<legend>Autenticação</legend>
						<label for="campoLogin">Usuário</label>
						<input type="text" value="" name="login" id="campoLogin">
						<label for="campoSenha">Senha</label>
						<input type="password" value="" name="senha" id="campoSenha">
					</fieldset>
					<br>
			    	<button type="submit" class="btn btn-primary btn-large">Entrar</button>
				</form>
			</div>
		</div>	
	</div>
	
	<script src="bibliotecas/js/head.min.js"></script>
	<script>
		head.ready(function() {
		   APP.iniciar();
		   APP.mostrarErro(<?php echo $mostrar;?>);
		});

		head.js.apply(head,[
			"bibliotecas/js/jquery-1.9.0.min.js", 
			"bibliotecas/js/APP.js"
		]);
		
		
	</script>
</body>
</html>