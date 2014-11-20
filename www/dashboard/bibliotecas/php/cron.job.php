<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include "class.MySQL.php";
$conn = new MySQL();
		
		$conn->ExecuteSQL("select * from usuario_sentimento where atualizado = 0");
		$num_rows = $conn->ArrayResults();
		
		$total = count($num_rows);
		
		if($total > 0) {
			 
			 foreach($num_rows as $row){
			   
			 	$url = "http://maps.google.com/maps/api/geocode/json?latlng=$row[latitude],$row[longitude]&sensor=false";
			    $data = json_decode(file_get_contents($url));
			    $cidade = $data->results[0]->address_components[3]->long_name;
			
			    $sql = "select * from cidade where nome like '$cidade'";
			    $conn->ExecuteSQL($sql);
			    $listaCidade = $conn->ArrayResult();
			    
			    $sql = $conn->ExecuteSQL("update usuario_sentimento set atualizado = 1, cidade_id = $listaCidade[id] where id = $row[id]");
				
			 }

		}else 
			echo "Não ha dados a serem atualizados"; 
		
