<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include "class.MySQL.php";
$conn = new MySQL();
		
		$conn->ExecuteSQL("select * from questionario_transporte");
		$num_rows = $conn->ArrayResults();
		
		$total = count($num_rows);
		
		if($total > 0) {
			 
		 $handle = fopen('venice_relatorio.csv','w+');

		 fwrite($handle,"Veiculos; Recurso Humanos; Medidas Aplicadas; Data e Hora; Usuario; Celular; Unidade; Sub Unidade; Latitude; Longitude");
		 	
		 foreach($num_rows as $row){
		   
		 	if($handle){
		 		$quebra = chr(13).chr(10);

		 		$linha = $row["veiculos"] . ';'. $row["recursos_humanos"] .';'. $row["medidas_aplicadas"] .';'. $row["data_hora"] . ';' . $row["usuario_id"]
		 		. ';' . $row["celular_id"] . ';' . $row["unidade"] . ';' . $row["subunidade"] . ';' . $row["latitude"]
		 		. ';' . $row["longitude"];
		 		//$linha .= "\n";
		 		fwrite($handle,$linha.$quebra);
		 	}
		 	
		 }

		 fclose($handle);
		}

		//forca o download do arquivo
		//header("Content-Type: $tipo");
		//header("Content-Length: $size");
		header("Content-Disposition: attachment; filename=venice_relatorio.csv;");
		//readfile($dir);
		//echo "true";