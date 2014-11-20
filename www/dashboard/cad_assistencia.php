<?php 
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
	include("bibliotecas/php/class.Auth.php");
	
	$auth = new Auth();
	$auth->notLogged("login.php");
		
		include("bibliotecas/php/class.MySQL.php");
		$db = new MySQL();
		$db->ExecuteSQL("select * from paises");
		$paises = $db->ArrayResults();
		
		$db->ExecuteSQL("select * from sinais_sintomas order by sinais_desc");
		$sinais_sintomas = $db->ArrayResults();
		
		$db->ExecuteSQL("select * from hipotese_diagnostica order by hip_dia_desc");
		$hipotese_diagnostica = $db->ArrayResults();
		
		$session = $auth->sessioninfo($_COOKIE["auth_session"]);
		$sql = "select ponto_monitoramento from users where id = " . $session['uid'];
		$db->ExecuteSQL($sql);
		$user = $db->ArrayResult();
		
		$subUnidade = false;
		$sqlComplemento = "";
		if($user["ponto_monitoramento"] > 0){
			$sql = "select id, unidade_id from sub_unidades where id = " . $user["ponto_monitoramento"]; 
			$db->ExecuteSQL($sql);
			$subUnidade = $db->ArrayResult();

			$sqlComplemento .= " and id = " . $subUnidade["unidade_id"];
			
		}
		
		$sql = "select * from unidades where ativo = 1 $sqlComplemento order by nome";
		$db->ExecuteSQL($sql);
		$unidades = $db->ArrayResults();
	
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Amber - Cadastro Assitência saúde</title>
	<link rel="stylesheet" href="bibliotecas/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="bibliotecas/bootstrap/css/bootstrap-datetimepicker.min.css">		
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
		      <li class="active dashboard"><a href="index.php">Dashboard</a></li>
		    </ul>
		   <?php include "menu.php"; ?>
		</div>
	</div>


	<!-- Seção Dashboard -->
	<div id="secao-dashboard" class="container-fluid secao">


		<div id="geralAssistencia" class="row-fluid">	
			<div class="hero-unit span8 offset2">
				
				<form method="post" action="bibliotecas/php/cad_assistencia.php">
				<div id="posicaoLanLog"></div>
					<fieldset>
						<legend>
							Cadastro Assitência saúde
							<?php if(isset($_GET["cad"])){ ?><span id="mensagem" class="label label-success">Cadastro realizado com sucesso<?php }?></span>	
						</legend>
						<span>01 - Local</span>
						<div class="well">
							<select name="local_atendimento" onchange="selecionaSubUnidades(this.value,false)" >
								<?php foreach ($unidades as $listaUnidades){?>
									<option value="<?php echo $listaUnidades["id"]; ?>"><?php echo utf8_encode($listaUnidades["nome"]); ?></option>
								<?php }?>
							</select>
							<div id="div_sub_unidades">
								<select id="sub_unidades" name="sub_unidade">
									<option value="0" selected="selected">Carregando...</option>
								</select>
							</div>	
						</div>	
						
						<span>02 - Público Atendido</span>
						<div class="well">
							<input type="radio" value="1" name="publico_atendido"  checked="checked" style="margin: 6px;">Expectador
							<input type="radio" value="2" name="publico_atendido" style="margin: 6px;">Trabalhador
							<input type="radio" value="3" name="publico_atendido" style="margin: 6px;">Outros
						</div>
						
						<span for="campoSenha">03 - Sexo</span>
						<div class="well">
							<div>
								<input type="radio" name="sexo" checked="checked" value="1" style="margin: 6px; margin-left: 8px">Masculino &nbsp;
								<input type="radio" name="sexo" value="2" style="margin: 6px;">Feminino
								<input type="radio" name="sexo" value="3" style="margin: 6px;">Ignorado
							</div>
						</div>
						
						<span for="campoSenha">04 - Idade</span>
						<div class="well">
							<div><input type="text" id="validaIdade" name="idade" maxlength="2"></div>
						</div>	
						
						<span >05 -Local de origem</span>
						<div class="well">
							<div id="div_pais">
								<select name="pais" onchange="selecionaEstado(this.value)">
									<option value="0" selected="selected">Selecione Pais</option>
									<?php foreach ($paises as $pais){ ?>
										<option value="<?php echo $pais["id"];?>" <?php echo ($pais["nome"] == "BRASIL")? "selected": "";?>><?php echo ucfirst( strtolower($pais["nome"]) );?></option>
									<?php }?>	
								</select>
								
							<div id="div_pais_outros" style="display:none;"><input type="text" name="pais_outros"></div>	
							</div>
							
							<div id="div_estados">
								<select id="estado" name="estado" onchange="selecionaCidade(this.value)">
									<option value="0" selected="selected">Carregando...</option>
								</select>
							</div>
							
							<div id="div_cidades" style="display:none;">	
								<select id="cidade" name="cidade">
									<option value="0" selected="selected">Carregando...</option>
								</select>
							</div>	
						</div>
							
						<span >06 - Hipótese Diagnóstica</span>
						<div class="well">
						<table width="100%">
							<?php 
							//echo count($hipotese_diagnostica);
							for($i=0; $i < count($hipotese_diagnostica); $i++){ ?>
								<tr>
									<td><input style="margin-top: -6px;" type="checkbox" name="hipotese_diagnostica[]" id="campo_hipotese_diagnostica_<?php echo $hipotese_diagnostica[$i]["hip_dia_id"]?>" value="<?php echo $hipotese_diagnostica[$i]["hip_dia_id"]?>"></td>
									<td><label class="fontFormulario" for="campo_hipotese_diagnostica_<?php echo $hipotese_diagnostica[$i]["hip_dia_id"]?>"><?php echo utf8_encode($hipotese_diagnostica[$i]["hip_dia_desc"]);?></label></td>
									<?php if(isset($hipotese_diagnostica[++$i]["hip_dia_id"]) != ""){?>
									<td>&nbsp;</td>
									<td><input style="margin-top: -6px;" type="checkbox" name="hipotese_diagnostica[]" id="campo_hipotese_diagnostica_<?php echo $hipotese_diagnostica[$i]["hip_dia_id"]?>" value="<?php echo $hipotese_diagnostica[$i]["hip_dia_id"]?>"></td>
									<td><label class="fontFormulario" for="campo_hipotese_diagnostica_<?php echo $hipotese_diagnostica[$i]["hip_dia_id"]?>"><?php echo utf8_encode($hipotese_diagnostica[$i]["hip_dia_desc"]);?></label></td>
									<?php }?>
								</tr>
							<?php }?>
							</table>
						</div>
						
						<span for="campoSenha">07 - Doença de notificação compulsória Imediata</span>
						<div class="well">
							<div>
								<input value="1" onclick="javascript:mudaCausas('causas_externas')" type="radio" name="dnci" style="margin: 6px;">Sim &nbsp;
								<input value="2" onclick="javascript:mudaCausas()" type="radio" checked="checked"  name="dnci" style="margin: 6px;">Não
								
								<div id="divSelecaoDNCI" style="display:none">
								<table width="100%">
								<?php $sql = "select * from suspeita_dnci";
									  $db->ExecuteSQL($sql);
									  $listaDnci = $db->ArrayResults();

									  for($i=0; $i < count($listaDnci); $i++){ ?>
										<tr>
											<td><input style="margin-top: -6px;" type="checkbox" name="suspeita_dnci[]" id="suspeita_dnci_<?php echo $listaDnci[$i]["id"];?>" value="<?php echo $listaDnci[$i]["id"];?>"></td>
											<td><label class="fontFormulario" for="suspeita_dnci_<?php echo $listaDnci[$i]["id"];?>"><?php echo utf8_encode($listaDnci[$i]["descricao"]);?></label></td>
											<?php if(isset($listaDnci[++$i]["id"]) != ""){?>
											<td>&nbsp;</td>
											<td><input style="margin-top: -6px;" type="checkbox" name="suspeita_dnci_[]" id="suspeita_dnci_<?php echo $listaDnci[$i]["id"]?>" value="<?php echo $listaDnci[$i]["id"]?>"></td>
											<td><label class="fontFormulario" for="suspeita_dnci_<?php echo $listaDnci[$i]["id"]?>"><?php echo utf8_encode($listaDnci[$i]["descricao"]);?></label></td>
										<?php }?>
										</tr>									
								<?php }?>
								</table>
								</div>
								
							</div>
						</div>
						
						<span >08 - Sinais e Sintomas</span>
						<div class="well">
							<table width="100%">
							<?php
							//echo count($sinais_sintomas); 
							for($i=0; $i < count($sinais_sintomas); $i++){ ?>
								<tr>
									<td><input style="margin-top: -6px;" type="checkbox" name="sinais_sintomas[]" id="campo_sinais_sintomas_<?php echo $sinais_sintomas[$i]["sinais_id"]?>" value="<?php echo $sinais_sintomas[$i]["sinais_id"]?>"></td>
									<td><label class="fontFormulario" for="campo_sinais_sintomas_<?php echo $sinais_sintomas[$i]["sinais_id"]?>"><?php echo utf8_encode($sinais_sintomas[$i]["sinais_desc"]);?></label></td>
									<td>&nbsp;</td>
									<?php $i++;?>
									<td><input style="margin-top: -6px;" type="checkbox" name="sinais_sintomas[]" id="campo_sinais_sintomas_<?php echo $sinais_sintomas[$i]["sinais_id"]?>" value="<?php echo $sinais_sintomas[$i]["sinais_id"]?>"></td>
									<td><label class="fontFormulario" for="campo_sinais_sintomas_<?php echo $sinais_sintomas[$i]["sinais_id"]?>"><?php echo utf8_encode($sinais_sintomas[$i]["sinais_desc"]);?></label></td>
									<td>&nbsp;</td>
									<?php $i++;?>
									<td><input style="margin-top: -6px;" type="checkbox" name="sinais_sintomas[]" id="campo_sinais_sintomas_<?php echo $sinais_sintomas[$i]["sinais_id"]?>" value="<?php echo $sinais_sintomas[$i]["sinais_id"]?>"></td>
									<td><label class="fontFormulario" for="campo_sinais_sintomas_<?php echo $sinais_sintomas[$i]["sinais_id"]?>"><?php echo utf8_encode($sinais_sintomas[$i]["sinais_desc"]);?></label></td>
								</tr>
							<?php }?>
							</table>
						</div>
							
						<span for="campoSenha">09 - Encaminhamentos</span>
						<div class="well">
							<div>
								<input type="radio" name="encaminhamentos" checked="checked" value="1" style="margin: 6px; margin-left: 8px">Alta &nbsp;
								<input type="radio" name="encaminhamentos" value="2" style="margin: 6px;">Óbito
								<input type="radio" name="encaminhamentos" value="3" style="margin: 6px;">Transferência	
							</div>
						</div>
						
						<span for="campoSenha">10 - Data e Hora</span>
						<div class="well">
						  <div id="datetimepicker1" class="input-append date">
						    <input id="campoDataHora" data-format="dd/MM/yyyy hh:mm:ss" type="text" name="data_cadastro" maxlength="19"></input>
						    <span class="add-on">
						      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
						      </i>
						    </span>
						  </div>
						</div>
						
						<span for="campoSenha">11 - Observação</span>
						<div class="well">
							<div>
								<textarea rows="5" cols="10" name="observacao"></textarea>	
							</div>
						</div>
						
					</fieldset>
					<button type="submit" class="btn btn-primary btn-large">Cadastrar</button>
			    	<?php if(isset($_GET["cad"])){ ?><span id="mensagem_1" class="label label-success">Cadastro realizado com sucesso<?php }?></span>
				</form>
			</div>
		</div>	
	</div>
	
	
	<script src="bibliotecas/js/head.min.js"></script>
	<script>

		function mudaCausas(causa){

			if(causa == 'causas_externas'){
				document.getElementById("divSelecaoDNCI").style.display = "block";
				//document.getElementById("divSelecaoClinicas").style.display = "none";
			}else{
				//document.getElementById("divSelecaoExternas").style.display = "none";
				//document.getElementById("divSelecaoClinicas").style.display = "block";
				document.getElementById("divSelecaoDNCI").style.display = "none";
			}
			
		}

		function selecionaEstado(valor){

			console.debug(valor);
			if(valor != 1){
				document.getElementById("div_pais_outros").style.display = "none";
				document.getElementById("div_estados").style.display = "none";
				document.getElementById("div_cidades").style.display = "none";

			}else{

				document.getElementById("div_cidades").style.display = "block";
				
				$.ajax({
			         type: "POST",
			         url: "bibliotecas/php/jsonEstado.php",
			         data: {id: valor},
			         dataType: "json",
			         success: function(json){
			        	var options = "";
			            $.each(json, function(key, value){
				            if(value.nome == "Pernambuco"){
				           		options += '<option value="' + value.id + '" selected >' + value.nome + '</option>';
				            }else{
				            	options += '<option value="' + value.id + '"   >' + value.nome + '</option>';
					        }	
			            });
			            $("#estado").html(options);
			         }
			      });
				
				document.getElementById("div_pais_outros").style.display = "none";
				document.getElementById("div_estados").style.display = "block";
			}
				
		}

		function selecionaCidade(valor){

			if(valor == 0){
				document.getElementById("div_cidades").style.display = "none";
			}else{

				$.ajax({
			         type: "POST",
			         url: "bibliotecas/php/jsonCidade.php",
			         data: {id: valor},
			         dataType: "json",
			         success: function(json){
			            var options = "";
			            $.each(json, function(key, value){
				           if(value.nome == "Recife"){
				        	   options += '<option value="' + value.id + '" selected >' + value.nome + '</option>';
					       }else{ 
				           	   options += '<option value="' + value.id + '">' + value.nome + '</option>';
					       }	   
			            });
			            $("#cidade").html(options);
			         }
			      });
				
				document.getElementById("div_pais_outros").style.display = "none";
				document.getElementById("div_cidades").style.display = "block";
			}

		}

		function selecionaSubUnidades(valor,ids){

			$.ajax({
		         type: "POST",
		         url: "bibliotecas/php/jsonSubUnidades.php",
		         data: {id: valor, subId: ids},
		         dataType: "json",
		         success: function(json){
		            var options = "";
		            $.each(json, function(key, value){
			           	   options += '<option value="' + value.id + '">' + value.nome + '</option>';
				    });
		            $("#sub_unidades").html(options);
		         }
		      });
		}

		function mostraDNCI(){

			if(document.getElementById("divSelecaoClinicas").value == 7){
				document.getElementById("divSelecaoDNCI").style.display = "block";
			}else{
				document.getElementById("divSelecaoDNCI").style.display = "none";
			}
			
		}

		function success(position) {

			$("#posicaoLanLog").html('<input type="hidden" name="latitude" value="' + position.coords.latitude + '"><input type="hidden" name="longitude" value="' + position.coords.longitude + '">');
			
		}

		function error(msg) {
			console.log(msg);
		}
			


		if (navigator.geolocation) {
		  navigator.geolocation.getCurrentPosition(success, error);
		} else {
			console.log("não tem suporte para geoLocation");
		}
	
		head.ready(function() {
		   APP.iniciar();
		   selecionaEstado(1);
		   selecionaCidade(16);
		   selecionaSubUnidades(<?php echo $unidades[0]["id"]?>,<?php echo $subUnidade["id"];?>);

		   setTimeout( "$('#mensagem').hide();",3000 );
		   setTimeout( "$('#mensagem_1').hide();",3000 );

		   $('#datetimepicker1').datetimepicker({
		      language: 'pt-BR'
		    });

		   $("#validaIdade").keydown(function (e) {
			   apenasNumeros(e);
		    });

		   $("#campoDataHora").keydown(function (e) {
			   apenasNumeros(e);
		    });
		   
		});

		head.js.apply(head,[
			"bibliotecas/js/jquery-1.9.0.min.js",
			"bibliotecas/js/bootstrap-datetimepicker.min.js",
			"bibliotecas/bootstrap/js/bootstrap.min.js", 
			"bibliotecas/js/APP.js"
		]);
	</script>
	
</body>
</html>