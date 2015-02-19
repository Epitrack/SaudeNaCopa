<!DOCTYPE HTML>
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Push Notification - Android</title>
	<script type="text/javascript" src="../bibliotecas/js/jquery-2.1.0.js"></script>
</head>
<body>
	<div>
		<ul id="info1">
                <li>Informe os dados para envio do push</li>
        </ul>
        <form id="form1">
             <!--  <label>Título</label> <br><input type="text" name="titulo" id="titulo"><br>-->
				<label>Mensagem</label><br><input type="text" name="mensagem" id="mensagem"><br>
			<label>Dias sem informar sentimento</label><br><input type="number" min="0" step="1" value="0" name="dias" id="dias"><br>
				<label>Idioma</label><br>
			<select name="idioma" id="idioma">
				<option value="0">Português</option>
				<option value="2">Inglês</option>
				<option value="1">Espanhol</option>
			</select><br>
			<label>Cidade (região metropolitana que foi enviado último sentimento)</label><br>
			<select name="cidade" id="cidade">
				<option value="Todas">Todas</option>
				<option value="Belo Horizonte">Belo Horizonte</option>
				<option value="Brasília">Brasília</option>
				<option value="Cuiabá">Cuiabá</option>
				<option value="Curitiba">Curitiba</option>
				<option value="Fortaleza">Fortaleza</option>
				<option value="Manaus">Manaus</option>
				<option value="Natal">Natal</option>
				<option value="Porto Alegre">Porto Alegre</option>
				<option value="Recife">Recife</option>
				<option value="Rio de Janeiro">Rio de Janeiro</option>
				<option value="Salvador">Salvador</option>
				<option value="São Paulo">São Paulo</option>
			</select><br>
			<label>Senha:</label><br><input type="password" name="senha" id="senha"><br>
			
                <input type="submit" name="submit" id="submit" value="Enviar">
        </form>
	</div>  
	<div id="status">
				
	</div>
	<div id="msg">
				
	</div>
        <script>
        $('#form1').submit(function(event) {
				$('#status').html("Aguarde... enviando.");
                event.preventDefault();
			for (var i = 0; i < 12; i++) { 
				setTimeout(enviaPush,((i+1)*1000),$(this).serialize()+"&pagina="+i);
			}
        });
			
			function enviaPush(parametros){
				 $.ajax({
                        type: 'POST',
                        url: 'push_android.php',
                        data: parametros,
                        dataType: 'json',
                        success: function (data) {
                            if(data.msg){
								$('#status').html("Pushs Enviados");
							}else{
								$('#msg').append("<br>Número de push enviado: "+data.success);
							}    
							
                        },
						
                });
				 
			}
        </script>
</body>
</html>