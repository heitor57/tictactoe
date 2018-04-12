<?php
	require("_con.php");
    $metodo = $_SERVER['REQUEST_METHOD'];
	$titulo = "Bem vindo a Página de Login";
	require("_header.php");
	session_start();
	$autenticado = false;
	if(isset($_SESSION['autenticado']) && $_SESSION['autenticado']=='true')
		$autenticado = true;

	if($autenticado) { ?>
		<h1>Você já está autenticado</h1>
		<h2>Clique aqui para voltar a página <a href="index.php">inicial</a></h2>

<?php	} elseif ($metodo == 'GET') {  ?>

	<h1>Formulário de Login</h1>
	<form action="login.php" method="post">
		<label>Nome de usuário: <input type="text" name="username"></label><br>
		<label>Senha: <input type="password" name="password"></label><br><br>
		<input type="submit" value="Entrar">
	</form>
	<form action="ranking.php">
		<input type="submit" value="Ranking">
	</form>
	<form action="registrarjogador.php">
		<input type="submit" value="Registrar">
	</form>

<?php	} elseif ($metodo == 'POST') {

		// usuario e senha fornecidos no formulário
		if(isset($_POST['username']))
			$user = $_POST['username'];
		if(isset($_POST['password']))
			$pass = $_POST['password'];

		// código original do password, deve ser obtido do banco de dados
		$stmt = $conexao->prepare("SELECT * FROM `jogador` WHERE `nome_j` = ?");
		$stmt->bindParam(1,$user);
		$stmt->execute();
		$retorno= $stmt->fetch();
		$hash_original = $retorno['senha_j'];
		$usuario = $retorno['nome_j'];


		// verificação final
		if(($usuario == $user) && password_verify($pass, $hash_original)) {
			$_SESSION['autenticado']=true;
			$_SESSION['username']=$user;
			header("Location: index.php");
		} else {
?>
			<h1>Falha de autenticação, tente novamente.</h1>
			<h2><a href="login.php">Login</a></h2>

<?php		}

	}
	require("_footer.php");
?>
