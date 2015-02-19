<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include("bibliotecas/php/class.Auth.php");
$auth = new Auth();$auth->logout();

$auth->register("terezacampos", "a!@#b!@#CD13", "a!@#b!@#CD13", "denniscalazans13@gmail.com"); $auth->logout();

$auth->register("eronildo", "a!@#b!@#CD14", "a!@#b!@#CD14", "denniscalazans14@gmail.com"); $auth->logout();

$auth->register("onicio", "a!@#b!@#CD15", "a!@#b!@#CD15", "denniscalazans15@gmail.com"); $auth->logout();

$auth->register("lucilene", "a!@#b!@#CD16", "a!@#b!@#CD16", "denniscalazans16@gmail.com"); $auth->logout();

$auth->register("dennis", "a!@#b!@#CD17", "a!@#b!@#CD17", "denniscalazans17@gmail.com"); $auth->logout();

$auth->register("cesar", "a!@#b!@#CD18", "a!@#b!@#CD18", "denniscalazans18@gmail.com"); $auth->logout();

//Para validar o usuário é preciso alterar na tabela users os campos isactive para 1, e activekey para 0
print_r($auth);
?>