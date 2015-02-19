<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
// ------------------------
// MySQL Configuration :
// ------------------------

//Antigo
//$db['host'] = "db.venice.denniscalazans.com";
//$db['user'] = "dbvenicepe";
//$db['pass'] = "Venice@PE123";
//$db['name'] = "db_venicepe";

//novo
//$db['host'] = "mysql.epitrack.com.br";
//$db['user'] = "rootepitrack13";
//$db['pass'] = "r7xD-Yin";
//$db['name'] = "_venice";

//local
$db['host'] = "mysql.saudenacopa.epitrack.com.br";
$db['user'] = "saudebacopadb";
$db['pass'] = "appcopa2014";
$db['name'] = "saudenacopa";

// ------------------------
// Auth Configuration :
// ------------------------

$auth_conf['site_name'] = "Venice"; // Name of website to appear in emails
$auth_conf['email_from'] = "no-reply@auth.cuonic.tk"; // Email FROM address for Auth emails (Activation, password reset...)
$auth_conf['max_attempts'] = 5; // INT : Max number of attempts for login before user is locked out
$auth_conf['base_url'] = "http://localhost/"; // URL to Auth Class installation root WITH trailing slash
$auth_conf['session_duration'] = "+1 month"; // Amount of time session lasts for. Only modify if you know what you are doing ! Default = +1 month
$auth_conf['security_duration'] = "+30 minutes"; // Amount of time to lock a user out of Auth Class afetr defined number of attempts.

$auth_conf['salt_1'] = 'us_$dUDN4N-53'; // Salt #1 for password encryption
$auth_conf['salt_2'] = 'Yu23ds09*d?'; // Salt #1 for password encryption

$loc = "br"; // Language of Auth Class output : br / en / fr

?>