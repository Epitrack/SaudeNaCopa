<?php 
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
@session_start();
//define("CONSULTA_TOTAL_ATENDIMENTOS", "SELECT count(id) as total FROM questionarios_assistencia");
define("CONSULTA_TOTAL_ATENDIMENTOS", "SELECT count(id) as total FROM assistencia where ativo = 1");

/*
define("CONSULTA_ATENDIMENTOS", "SELECT su.id, su.nome, count(q.sub_unidade_id) as total
						FROM sub_unidades su
						LEFT JOIN questionarios_assistencia q 
						ON su.id = q.sub_unidade_id
						GROUP BY q.sub_unidade_id, su.nome
						ORDER BY su.id ASC");
*/


// Amber
define("CONSULTA_PORCENTAGEM_SEXO", "SELECT u.sexo as tipo,
								   ROUND(((count(u.sexo) * 100)/t.contagem),2) as percent 
								FROM usuarios u,
								(select count(sexo) as contagem from usuarios) t
								GROUP BY u.sexo");



define("CONSULTA_ATENDIMENTOS", "SELECT su.id, su.nome, count(q.local_id) as total
						FROM sub_unidades su
						LEFT JOIN assistencia q 
						ON su.id = q.local_id and q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
						where su.ativo = 1
						GROUP BY q.local_id, su.nome
						ORDER BY su.id ASC");

define("CONSULTA_VIGILANCIA", "select tq.nome as nome, tq.id as id, count(vs.id) as total 
								from tabelas_questionarios tq
								LEFT JOIN vigilancia_sanitaria vs on tq.id = vs.locais_id
									AND vs.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
									AND vs.ativo = 1
								where tq.tabela <> 'surto'
								GROUP BY tq.id
								ORDER BY tq.id");

define("CONSULTA_VIGILANCIA_PERIODICA", "select tq.id as i, count(vs.id) as t 
								from tabelas_questionarios tq
								LEFT JOIN vigilancia_sanitaria vs on tq.id = vs.locais_id
									AND vs.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
									AND vs.ativo = 1
								where tq.tabela <> 'surto'
								GROUP BY tq.id
								ORDER BY tq.id");


define("CONSULTA_ATENDIMENTOS_VIGILANCIA_HORA_EM_HORA", "SELECT   HOUR(data_hora) AS x, COUNT(*) AS 'y'
											FROM     vigilancia_sanitaria
											WHERE    data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
											and ativo = 1
											GROUP BY HOUR(data_hora)");

// define("CONSULTA_ATENDIMENTOS", "SELECT count(assistencia_id) as total from assistencia");

/*
define("CONSULTA_ATENDIMENTOS_PERIODICA", "SELECT su.id as i, count(q.sub_unidade_id) as t
						FROM sub_unidades su
						LEFT JOIN questionarios_assistencia q 
						ON su.id = q.sub_unidade_id
						GROUP BY q.sub_unidade_id, su.nome
						ORDER BY su.id ASC");
*/
define("CONSULTA_ATENDIMENTOS_PERIODICA", "SELECT su.id as i, count(q.local_id) as t
						FROM sub_unidades su
						LEFT JOIN assistencia q 
						ON su.id = q.local_id and q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
						where su.ativo = 1
						GROUP BY q.local_id, su.nome
						ORDER BY su.id ASC");

//consulta de assitencia de hora em hora;
define("CONSULTA_ATENDIMENTOS_HORA_EM_HORA", "SELECT   HOUR(data_hora) AS x, COUNT(*) AS 'y'
											FROM     assistencia
											WHERE    data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
											and ativo = 1
											GROUP BY HOUR(data_hora)");
/* 
	define("CONSULTA_ATENDIMENTOS_HORA_EM_HORA", "SELECT   CONCAT(HOUR(data_hora), ':00-', HOUR(data_hora)+1, ':00') AS hora, COUNT(*) AS 'total'
											FROM     assistencia
											WHERE    data_hora BETWEEN '2013-05-18' AND NOW()
											GROUP BY HOUR(data_hora)");
*/
//consulta de assistencia de encaminhamento com porcentagem
define("CONSULTA_ATENDIMENTOS_PORCENTAGEM", "SELECT encaminhamentos, count(*) as total, count(*) / t.total * 100 as porcento 
											FROM assistencia, 
												(SELECT count(*) as total FROM assistencia 
													where ativo = 1
													and data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
												) as t 
											where ativo = 1
											and data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59' 
											GROUP BY encaminhamentos");


?>