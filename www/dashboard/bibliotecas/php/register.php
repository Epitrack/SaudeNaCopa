<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include("bibliotecas/php/class.Auth.php");
$auth = new Auth();
$auth->logout();
$auth->register("saudepegovbr01", "a!@#b!@#CD01", "a!@#b!@#CD01", "denniscalazans1@gmail.com"); $auth->logout();
$auth->register("saudepegovbr02", "a!@#b!@#CD02", "a!@#b!@#CD02", "denniscalazans2@gmail.com"); $auth->logout();
$auth->register("saudepegovbr03", "a!@#b!@#CD03", "a!@#b!@#CD03", "denniscalazans3@gmail.com"); $auth->logout();
$auth->register("saudepegovbr04", "a!@#b!@#CD04", "a!@#b!@#CD04", "denniscalazans4@gmail.com"); $auth->logout();
$auth->register("saudepegovbr05", "a!@#b!@#CD05", "a!@#b!@#CD05", "denniscalazans5@gmail.com"); $auth->logout();
$auth->register("saudepegovbr06", "a!@#b!@#CD06", "a!@#b!@#CD06", "denniscalazans6@gmail.com"); $auth->logout();
$auth->register("saudepegovbr07", "a!@#b!@#CD07", "a!@#b!@#CD07", "denniscalazans7@gmail.com"); $auth->logout();
$auth->register("saudepegovbr08", "a!@#b!@#CD08", "a!@#b!@#CD08", "denniscalazans8@gmail.com"); $auth->logout();
$auth->register("saudepegovbr09", "a!@#b!@#CD09", "a!@#b!@#CD09", "denniscalazans9@gmail.com"); $auth->logout();
$auth->register("saudepegovbr10", "a!@#b!@#CD10", "a!@#b!@#CD10", "denniscalazans10@gmail.com"); $auth->logout();
$auth->register("saudepegovbr11", "a!@#b!@#CD11", "a!@#b!@#CD11", "denniscalazans11@gmail.com"); $auth->logout();
$auth->register("saudepegovbr12", "a!@#b!@#CD12", "a!@#b!@#CD12", "denniscalazans12@gmail.com"); $auth->logout();

//Para validar o usuário é preciso alterar na tabela users os campos isactive para 1, e activekey para 0
print_r($auth);
?>