<?php
if(isset($_POST["username"])){
@session_start();
	
	include "../php/class.Auth.php";
	$auth = new Auth();
	
	if($auth->register($_POST["username"], $_POST["password"], $_POST["re-password"], $_POST["email"])){
		$_SESSION["errormsg"] = "Cadastro realizado com sucesso!";
	}else{
		$_SESSION["errormsg"] = $auth->errormsg;
	}
}
header("Location: cad_user.php");
?>


