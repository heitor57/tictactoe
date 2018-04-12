<?php
$metodo = $_SERVER['REQUEST_METHOD'];
$conexao= new PDO('mysql:host=localhost;dbname=jogodavelha;charset=utf8','root','');
$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
<html>
<head>
	<meta charset="utf-8">
	<title>Pagina inicial</title>

</head>
<body>
	<form method="POST">
		<b>Usuário </b><input type="text" name="usuario"><br>
		<b>Senha </b><input type="password" name="senha"><br>
		<input type="submit" value="Cadastrar">
	</form>
	<?php
	if($metodo == "POST" && !empty($_POST['usuario']) && !empty($_POST['senha'])){
		try{
			$stmt=$conexao->prepare("INSERT INTO `jogador`(`nome_j`, `senha_j`) VALUES (?,?)");
			$stmt->bindParam(1,$_POST['usuario']);
			$hash = password_hash( $_POST['senha'] , PASSWORD_BCRYPT);
			$stmt->bindParam(2,$hash);
			$stmt->execute();
		}catch (Exception $e){
			echo "<br><b>Ocorreu o seguinte erro:<br></b>".$e."";
		}

	}
	?>
</body>
</html>
