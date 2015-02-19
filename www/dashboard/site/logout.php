<?php 
@session_start();
$_SESSION["auth_session"] = "";
unset($_SESSION["auth_session"]);
header("Location: home");	
?>