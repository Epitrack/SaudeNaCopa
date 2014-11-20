<?php 
//id	username	password	email
@session_start();
include "../php/class.MySQL.php";
$conn = new MySQL();

if(isset($_SESSION["errormsg"])){
	
	if(is_array($_SESSION["errormsg"])){
		
		foreach($_SESSION["errormsg"] as $list){
			echo utf8_decode($list) . "<br>";
		}
		
	}else{
		echo $_SESSION["errormsg"];
	}
unset($_SESSION["errormsg"]);	
}

if(isset($_GET["alt"])){
	
	$sql = "SELECT * FROM users where id = " . $_GET["alt"];
	$conn->ExecuteSQL($sql);
	$dados = $conn->ArrayResult();
	
}

?>
<form action="cad_user01.php" method="post">
<table>
	<tr>
		<td>Nome</td>
		<td><input name="username" type="text"></td>
	</tr>
	<tr>
		<td>e-mail</td>
		<td><input name="email" type="text"></td>
	</tr>
	<tr>
		<td>Senha</td>
		<td><input name="password" type="password"></td>
	</tr>
	<tr>
		<td>Confirma Senha</td>
		<td><input name="re-password" type="password"></td>
	</tr>
	
	<tr>
		<td colspan="2"><input name="cad" type="submit" value="Cadastrar"></td>
	</tr>
</table>
</form>
<hr>

<table border="1">
	<tr>
		<td>Nome</td>
		<td>e-mail</td>
	</tr>
	
	<?php 
			
		$sql = "SELECT * FROM users where isactive=1";
		$conn->ExecuteSQL($sql);
		$dados = $conn->ArrayResults();
	
		foreach ($dados as $lista){
			
			echo "<tr>
					<td>" . $lista["username"] . "</td>
					<td>" . $lista["email"] . "</td>
				  </tr>";		
			
		}
		
		//					<td colspan=2>
		//				<a href='cad_user.php?alt=" . $lista["id"] . "'>Alterar</a> 
		//				<a href='cad_user01.php?exl=" . $lista["id"] . "'>Excluir</a>
		//			</td>
		
	?>
	
	
	
</table>

