<?php
include "../php/class.MySQL.php";
$conn = new MySQL();

function replaceChars($name) {
	$name = utf8_decode ($name);
	$newName = $name;
	$qtd = strlen ($name);
	for ($i = 0; $i < $qtd; $i++) {
		$char = substr($name, $i, 1);
		$code = ord ($char);
		//echo $char . " => " . $code . "<br/>";
		if ($code == 92 || $code == 47 ) {
			continue;
		}
		if (($code > 122) || ($code > 90 && $code < 97) || ($code > 57 && $code < 65) || ($code < 48 && $code != 46)) {
			$newName = str_replace ($char, '_', $newName);
		}
	}
	return $newName;
}


function validaCidade($cidade, $arr){

	$boo = false;

	for ($i=0; $i < count($arr); $i++) {

		if( $cidade == replaceChars($arr[$i]['nome']) ){
			$boo = true;
			break;
		}
	}

	return $boo;
}


function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}




$sql = "SELECT
			count(us.id) as total,
			count(us.sentimento) as sentimento,
			us.cidade_regiao_metro as nome,

			sum(us.campo1) as campo1,
			sum(us.campo2) as campo2,
			sum(us.campo3) as campo3,
			sum(us.campo4) as campo4,
			sum(us.campo5) as campo5,
			sum(us.campo6) as campo6,
			sum(us.campo7) as campo7,
			sum(us.campo8) as campo8,
			sum(us.campo9) as campo9,
			sum(us.campo10) as campo10,
			sum(us.campo11) as campo11,
			sum(us.campo12) as campo12
			FROM usuarios u
			INNER JOIN usuario_sentimento us ON us.usuario_id = u.id

			where us.sentimento in (3,4)
			and us.cidade_regiao_metro <> ''
			GROUP BY us.cidade_regiao_metro";
//INNER JOIN cidades c ON c.id = us.cidade_id

$conn->ExecuteSQL($sql);
$dados = $conn->ArrayResults();


/*
$sql = "select * from sentimentos";
$conn->ExecuteSQL($sql);
$sentimentos = $conn->ArrayResults();
*/
$arrayCidadeSedes = array(
					"Recife", "Natal", "Manaus", "Fortaleza", 
					"Salvador", "Cuiabá", "Brasília", "Belo Horizonte", 
					"São Paulo", "Rio de Janeiro", "Curitiba", "Porto Alegre"
					);

sort($arrayCidadeSedes);


$arrayCidadeSedesLimpo = array();
for($i=0;$i<=11;$i++){
	$arrayCidadeSedesLimpo[] = replaceChars($arrayCidadeSedes[$i]);
}

//print_r($arrayCidadeSedesLimpo);

?>
<!doctype html>
<html lang="en">
<head>
	<link rel="stylesheet" href="dist/css/styles.combined.min.css">
</head>
<body data-screen="modal">

<div id="modal">
	<div class="title-modal">Sintomas por Cidade-sede</div>


	<ul class="list-modal">
		<li>Cidades</li>
		<?php
		$campo1=0;
		$total=0;
		$sentimentosParcial = array();


		//echo "<pre>";

			//	die();
		for ($i=0; $i < count($dados); $i++) {
			
			$dado = $dados[$i];
			$key = array_search(ucwords(replaceChars($dado["nome"])), $arrayCidadeSedesLimpo);


			//echo $key." >>".ucwords($dado["nome"]) ." = !". ucwords(replaceChars($dado["nome"]))."!<br>";

			if($key === false){
				//var_dump($key);
				 array_splice($dados,$i,1);
			}

		}



//var_dump($dadoAtual);
		if( count($dados) < 12 ){

			for ($i=0; $i < count($arrayCidadeSedesLimpo); $i++) {

				if( !validaCidade( $arrayCidadeSedesLimpo[$i], $dados) ){


							$novoArray = array();
							$novoArray["campo1"] = 0;
							$novoArray["campo2"] = 0;
							$novoArray["campo3"] = 0;
							$novoArray["campo4"] = 0;
							$novoArray["campo5"] = 0;
							$novoArray["campo6"] = 0;
							$novoArray["campo7"] = 0;
							$novoArray["campo8"] = 0;
							$novoArray["campo9"] = 0;
							$novoArray["campo10"] = 0;
							$novoArray["campo11"] = 0;
							$novoArray["campo12"] = 0;

							$novoArray["sentimento"] = 0;

							$novoArray["total"] = 0;
							$novoArray["nome"] = $arrayCidadeSedes[$i];



							$dadoAtual = array();
							$dadoAtual[0] = $novoArray;

								for ($j=0; $j < count($dados); $j++) {
									$dadoAtual[] = $dados[$j];
								}

								//var_dump($dadoAtual);

							$dados = array_orderby($dadoAtual, 'nome', SORT_ASC);


				}
			}

		}

 

		foreach($dados as $dado){

			//echo ucwords($dado["nome"]) . "<br>";

			$key = array_search(ucwords(replaceChars($dado["nome"])), $arrayCidadeSedesLimpo);

			//echo "<pre>";
			//var_dump($key);

			if($key !== false){

				$total += (int)$dado["total"];
				//print_r($total);

				$arrayCampo1["valor"][] = $dado["campo1"]; $campo1 += $dado["campo1"]; 
				$arrayCampo2["valor"][] = $dado["campo2"]; $campo2 += $dado["campo2"];
				$arrayCampo3["valor"][] = $dado["campo3"]; $campo3 += $dado["campo3"];
				$arrayCampo4["valor"][] = $dado["campo4"]; $campo4 += $dado["campo4"];
				$arrayCampo5["valor"][] = $dado["campo5"]; $campo5 += $dado["campo5"];
				$arrayCampo6["valor"][] = $dado["campo6"]; $campo6 += $dado["campo6"];
				$arrayCampo7["valor"][] = $dado["campo7"]; $campo7 += $dado["campo7"];
				$arrayCampo8["valor"][] = $dado["campo8"]; $campo8 += $dado["campo8"];
				$arrayCampo9["valor"][] = $dado["campo9"]; $campo9 += $dado["campo9"];
				$arrayCampo10["valor"][] = $dado["campo10"]; $campo10 += $dado["campo10"];
				
				$arrayCampo11["valor"][] = $dado["total"];
				
				//TOTAL POR CIDADE
				$sqlTotalCidades = "SELECT count(us.sentimento) as total
							FROM usuarios u
							INNER JOIN usuario_sentimento us ON us.usuario_id = u.id
							where us.cidade_regiao_metro <> '' AND 
							cidade_regiao_metro = CAST(_latin1 '".$dado["nome"] ."'  AS CHAR CHARACTER SET utf8)
							GROUP BY us.cidade_regiao_metro";



				$conn->ExecuteSQL($sqlTotalCidades);
				$dadosGeralCidade = $conn->ArrayResults();
				

				$arrayCampo12["valor"][] = $dadosGeralCidade[0]["total"];

				$totalSentimentos = $campo1 + $campo2 + $campo3 + $campo4 + $campo5
									+ $campo6+ $campo7 + $campo8 +$campo9 + $campo10;
			
		?>
			<li><?php echo ($dado["nome"]);?></li>
	  <?php 
			}else{

			}

		}



		$dado = $dados;

		for ($i=0; $i < count($dado); $i++) { 
			$atual = $dado[$i];
			//for ($j=0; $j < count($atual["campo1"]); $j++) { 

			$sentimentosParcial[] = $atual["campo1"] + 
									$atual["campo2"] + 
									$atual["campo3"] + 
									$atual["campo4"] + 
									$atual["campo5"] + 
									$atual["campo6"] + 
									$atual["campo7"] + 
									$atual["campo8"] + 
									$atual["campo9"] + 
									$atual["campo10"];
		
		}
 
		//print_r($sentimentosParcial);
		//die();



		//echo $total . " 1=>" . $campo1. " 2=>" . $campo2. " 3=>" . $campo3. " 4=>" . 
		//						$campo4. " 5=>" . $campo5. " 6=>" . $campo6 . " 7=>"
		//						. $campo7. " 8=>" . $campo8. " 9=>" . $campo9. " 10=>" . $campo10;
		

		echo "<li>Total</li>";
  		
		$arrayCampo1["total"][] = number_format(($campo1*100/$totalSentimentos),2). "%";
		$arrayCampo1["total_abs"][] = $campo1;
		
		$arrayCampo2["total"][] = number_format(($campo2*100/$totalSentimentos),2). "%";
		$arrayCampo2["total_abs"][] = $campo2;
		
		$arrayCampo3["total"][] = number_format(($campo3*100/$totalSentimentos),2). "%";
		$arrayCampo3["total_abs"][] = $campo3;
		
		$arrayCampo4["total"][] = number_format(($campo4*100/$totalSentimentos),2). "%";
		$arrayCampo4["total_abs"][] = $campo4;
		
		$arrayCampo5["total"][] = number_format(($campo5*100/$totalSentimentos),2). "%";
		$arrayCampo5["total_abs"][] = $campo5;
		
		$arrayCampo6["total"][] = number_format(($campo6*100/$totalSentimentos),2). "%";
		$arrayCampo6["total_abs"][] = $campo6;
		
		$arrayCampo7["total"][] = number_format(($campo7*100/$totalSentimentos),2). "%";
		$arrayCampo7["total_abs"][] = $campo7;
		
		$arrayCampo8["total"][] = number_format(($campo8*100/$totalSentimentos),2). "%";
		$arrayCampo8["total_abs"][] = $campo8;
		
		$arrayCampo9["total"][] = number_format(($campo9*100/$totalSentimentos),2). "%";
		$arrayCampo9["total_abs"][] = $campo9;
		
		$arrayCampo10["total"][] = number_format(($campo10*100/$totalSentimentos),2). "%";
		$arrayCampo10["total_abs"][] = $campo10;
		

		$camposLabel = array(
							
							'Febre' , 
							'Tosse' , 
							'Dor de Garganta' , 
							'Falta de ar' , 
							'Náusea e vômitos' , 
							'Diarréia' , 
							'Dor nas articulações' , 
							'Dor de cabeça' , 
							'Sangramento' , 
							'Manchas vermelhas no corpo',
							'Com Sintomas',
							'Total'

 						); 
		?>
	</ul>	

<?

	for ($i=0; $i < count($camposLabel); $i++) { 
		$campo = 'arrayCampo'.($i+1);
		$arr = $$campo;
		
		?>

			<ul class="list-modal">
				<li><?echo $camposLabel[$i] ?></li>

				<?php 
				$somadorUsuarios = 0;
				for ($j=0; $j < count($arr["valor"]); $j++) {  
					$somadorUsuarios += $arr["valor"][$j];
				}


				for ($j=0; $j < count($arr["valor"]); $j++) {  

					if($i < 10){
						$lista = $arr["valor"][$j];
	 					$denominador = $sentimentosParcial[$j] > 0? $sentimentosParcial[$j] : 1;
						echo "<li>$lista    (" . number_format( $lista / $denominador * 100, 2 ) . "%)</li>";	
					}else{
						if($arr["valor"][$j] == ""){
							echo "<li>0 (0.00%)</li>";
						}else{
							echo "<li>". $arr["valor"][$j] . " (" . number_format( $arr["valor"][$j] / $somadorUsuarios * 100, 2 ) . "%)" ."</li>";
							
						}
					}
				}?>
				<?php if($i < 10){ ?>
					<li><?php echo $arr["total_abs"][0] . " (" . $arr["total"][0] . ")"; ?></li>
				<?php }else{
						echo "<li>" . $somadorUsuarios . " </li>";
					  }
				?>
			</ul>

		<?
	}
	
?>	
 
	<a href="javascript:;" title="Clique para fechar" class="btn-close">Fechar</a>
</div>
</body>
</html>