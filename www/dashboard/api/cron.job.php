<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include("../php/class.MySQL.php");
$conn = new MySQL();
		
		$conn->ExecuteSQL("select * from usuario_sentimento where atualizado = 0 and latitude <> 0");
		$num_rows = $conn->ArrayResults();
		
		echo "Total:" . $total = count($num_rows);
		echo "<br>";
		$contador=0;
		if($total > 0) {
			 
			try{
			 
				 foreach($num_rows as $row){
				   
				 	$contador++;
				 	echo $url = "http://maps.google.com/maps/api/geocode/json?latlng=$row[latitude],$row[longitude]&sensor=false";
				 	echo "<br>";
				    $data = json_decode(file_get_contents($url));

				    if(isset($data->error_message)){
				    	die($data->error_message);	
				    }
				    
				    foreach($data->results[0]->address_components as $address_components){
				    
				    	//echo "<pre>";
						$nomeAchado = utf8_decode($address_components->long_name);
						//echo $nomeAchado = stringExpresaoRegular($nomeAchado);
				    	echo "<br>";
				    	echo $sql = "select * from cidades where nome LIKE '$nomeAchado'";
				    	$conn->ExecuteSQL($sql);
						$listaCidade = $conn->ArrayResults();
						
						echo $entrou = count($listaCidade);
						if($entrou >= 1){
							echo "entrou<br>";
							atualizaCidade($conn, $listaCidade, $row["id"]);
						}else{
							echo "não entrou<br>";
						}
				    	
				    }
				 echo "<br>##################################<br>";   
				 }
			}catch (Exception $e){
				echo $e->getMessage();
			}	 

		}else 
			echo "Não ha dados a serem atualizados"; 
		

function atualizaCidade($conn,$listaCidade, $id){

		echo $sqlQuery = "update usuario_sentimento set atualizado = 1, cidade_id = " . $listaCidade[0]["id"] . " where id = " . $id;
		echo "<br>";
		$sql = $conn->ExecuteSQL($sqlQuery);
		
}	


function stringExpresaoRegular($str){

	$map = array(
    'á' => '.*',
    'à' => '.*',
    'ã' => '.*',
    'â' => '.*',
    'é' => '.*',
    'ê' => '.*',
    'í' => '.*',
    'ó' => '.*',
    'ô' => '.*',
    'õ' => '.*',
    'ú' => '.*',
    'ü' => '.*',
    'ç' => '.*',
    'Á' => '.*',
    'À' => '.*',
    'Ã' => '.*',
    'Â' => '.*',
    'É' => '.*',
    'Ê' => '.*',
    'Í' => '.*',
    'Ó' => '.*',
    'Ô' => '.*',
    'Õ' => '.*',
    'Ú' => '.*',
    'Ü' => '.*',
	);
 
echo strtr($str, $map);
	
} 
