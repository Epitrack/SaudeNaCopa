<?php 
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
@session_start();

define("CONSULTA_PORCENTAGEM_SEXO", "SELECT u.sexo as tipo,
									   ROUND(((count(u.sexo) * 100)/t.contagem),2) as percent 
									FROM usuarios u,
									(select count(sexo) as contagem from usuarios) t
									GROUP BY u.sexo");

define("CONSULTA_PORCENTAGEM_CONTATO_CONHECO_ALGUEM", "SELECT us.campo11 as nome,
														ROUND(((count(us.campo11) * 100)/t.contagem),2) as percent 
														FROM usuario_sentimento us,
														(select count(campo11) as contagem from usuario_sentimento) t
														GROUP BY us.campo11");

define("CONSULTA_PORCENTAGEM_PROCUREI_SERVICO_SAUDE", "SELECT us.campo12 as nome,
														ROUND(((count(us.campo12) * 100)/t.contagem),2) as percent 
														FROM usuario_sentimento us,
														(select count(campo12) as contagem from usuario_sentimento) t
														GROUP BY us.campo12");


?>