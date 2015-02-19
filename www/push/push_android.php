<?php

$return = array();
$return['msg'] = '';
// apikey do app no google play
$apiKey = "AIzaSyCqn0s3iFP53fhhHB-6c2-zT5HTpoi8Lqk";

//PEGAR OS PARAMETROS PASSADOS DO POST
$senha = $_POST["senha"];
if($senha != "saude23dg"){
	$return['msg'] = "Senha não confere";
	echo  json_encode($return);
	exit();
}
//Pegar do post o título a ser enviado
$titulo = "Saúde na Copa";

// Pegar do post a mensagem a ser enviada
$message = $_POST["mensagem"];

//pegar do post a cidade
$cidade = $_POST["cidade"];

//dias para verificar se o usuario enviou um sentimento 
//exemplo: 3 dias que o usuario nao informou um sentimento, o sistema filtra aqueles que se enquadra nesse quesito
//se dias ==0 entao envia para todos
$dias = $_POST["dias"];
//Idioma
$idioma = $_POST["idioma"];

//pagina
$page = $_POST["pagina"];
$page = $page*500;

if(empty($message)){
	$return['msg'] = "Informe mensagem";
	echo  json_encode($return);
	exit();
}

//tem que buscar os registros de gcmid de acordo com o idioma selecionado.
//mudar a conexao
include 'conexao_i.php';

//deve passar o idioma selecionado na query

$blackList[0]="";
if($dias > 0){
	//buscar lista dos que nao vao receber push
	$statement = $mysqli->prepare("select distinct(usuario_id) from usuario_sentimento where `data_cadastro` > DATE_SUB(NOW(), INTERVAL ? DAY)");
	$statement->bind_param('i', $dias);
	$statement->bind_result($usuario_id);
	$statement->execute();
	$j=0;
	while($statement->fetch()){
		$blackList[$j]=$usuario_id;
		$j++;
	}
	$statement->close();
	
	
}
$statement = $mysqli->prepare("SELECT `gcmid`,id FROM `usuarios` where idioma = ? and device = 'android' limit ?,500");
$statement->bind_param('ii', $idioma,$page);


$statement->bind_result($registro,$usuario_id);
$statement->execute();
$i=0;
 while($statement->fetch()){
	 if(($registro != 0 || !empty($registro)) && !in_array($usuario_id, $blackList)){
		$registrationIDs[$i]=$registro;
		 $usersId[$i]=$usuario_id;
   		$i++;
	 }   
 }

$statement->close();

//verifica se foi selecionada cidade

if($cidade !="Todas"){
	$i=0;
	for($j=0; $j < count($usersId); $j++){
		$statement = $mysqli->prepare("SELECT usuario_id  FROM `usuario_sentimento` where usuario_id = ?  and cidade_regiao_metro = ?  order by id desc limit 1");
		$statement->bind_param('is', $usersId[$j], $cidade  );
		$statement->bind_result($usuarioid);
		$statement->execute();
    	while($statement->fetch()){			
			if(!empty($usuarioid)){
				$gcmIds[$i]=$registrationIDs[$j];
				$i++;				
			}
		 }
		
		$statement->close();
	}
	$mysqli->close();
	$registrationIDs = $gcmIds;
}

//se nao encontrou nenhum nao enviar
if($i==0){
	$return['msg'] = "Fim";
	echo  json_encode($return);
	exit();
}else{
	$return['success'] = count($registrationIDs);
	echo  json_encode($return);
}


// Set POST variables
$url = 'https://android.googleapis.com/gcm/send';

$fields = array(
    'registration_ids'  => $registrationIDs,
    'data'              => array("title"=>$titulo, "message" => $message,"msgcnt"=>"1"  ),
);

$headers = array(
    'Authorization: key=' . $apiKey,
    'Content-Type: application/json'
);

// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt( $ch, CURLOPT_URL, $url );

curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

// Execute post
$result = curl_exec($ch);

// Close connection
curl_close($ch);

//echo $result;

/* $return['msg'] = $result;
echo  json_encode($return); */

?>