<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include("bibliotecas/php/class.Auth.php");
$auth = new Auth();
$auth->logout("login.php");

?>