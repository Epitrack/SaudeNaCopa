<?php
include "class.MySQL.php";
$conn = new MySQL();

$sql = "SELECT
			count(us.id) as total, 
			c.nome as nome, 
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
			FROM usuario_sentimento us
			INNER JOIN cidades c ON c.id = us.cidade_id
			GROUP BY c.id";
$conn->ExecuteSQL($sql);
$dados = $conn->ArrayResults();

$sql = "select * from sentimentos";
$conn->ExecuteSQL($sql);
$sentimentos = $conn->ArrayResults();

?>

<table border="1">
	<tr>
		<td>Cidade</td>
		<?php foreach($sentimentos as $sentimento){ ?>
		<?php 
				if($contador <=9){
					echo "<td>" . $sentimento["nome"] . "</td>";		
				}
				$contador++;
			?>
		<?php }?>
	</tr>
	<?php 
	$campo1=0;
	foreach($dados as $dado){ 
	$total+=$dado["total"];
	?>
	<tr>
		<td><?php echo $dado["nome"];?></td>
		<td><?php echo $dado["campo1"]; $campo1 += $dado["campo1"]; ?></td>
		<td><?php echo $dado["campo2"]; $campo2 += $dado["campo2"];?></td>
		<td><?php echo $dado["campo3"]; $campo3 += $dado["campo3"];?></td>
		<td><?php echo $dado["campo4"]; $campo4 += $dado["campo4"];?></td>
		<td><?php echo $dado["campo5"]; $campo5 += $dado["campo5"];?></td>
		<td><?php echo $dado["campo6"]; $campo6 += $dado["campo6"];?></td>
		<td><?php echo $dado["campo7"]; $campo7 += $dado["campo7"];?></td>
		<td><?php echo $dado["campo8"]; $campo8 += $dado["campo8"];?></td>
		<td><?php echo $dado["campo9"]; $campo9 += $dado["campo9"];?></td>
		<td><?php echo $dado["campo10"]; $campo10 += $dado["campo10"];?></td>
	</tr>
	<?php }?>
	<tr>
		<td>Total</td>
		<td><?php echo number_format(($campo1*100/$total));?>%</td>
		<td><?php echo number_format(($campo2*100/$total));?>%</td>
		<td><?php echo number_format(($campo3*100/$total));?>%</td>
		<td><?php echo number_format(($campo4*100/$total));?>%</td>
		<td><?php echo number_format(($campo5*100/$total));?>%</td>
		<td><?php echo number_format(($campo6*100/$total));?>%</td>
		<td><?php echo number_format(($campo7*100/$total));?>%</td>
		<td><?php echo number_format(($campo8*100/$total));?>%</td>
		<td><?php echo number_format(($campo9*100/$total));?>%</td>
		<td><?php echo number_format(($campo10*100/$total));?>%</td>
		
	</tr>
	<?php 
		//echo $totalTodos = $campo1 + $campo2 + $campo3 + $campo4 + $campo5 + $campo6 + $campo7 + $campo8 + $campo9 + $campo10 + $campo11;
	?>
	<!-- 
	
	<tr>
		<td>Total teste</td>
		<td><?php //echo number_format(($campo1*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo2*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo3*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo4*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo5*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo6*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo7*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo8*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo9*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo10*100/$totalTodos));?>%</td>
		<td><?php //echo number_format(($campo11*100/$total));?>%</td>
		<td><?php //echo number_format(($campo12*100/$total));?>%</td>
	</tr>
	
	 -->
	
	
</table>
