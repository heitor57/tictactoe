<div style="text-align: center; border: 1px dashed blue;margin-left: 35%;margin-right: 35%;background: grey;">
<?php
$metodo = $_SERVER['REQUEST_METHOD'];
require("_con.php");
$titulo = "Configurações";
require("_header.php");
session_start();
$autenticado = false;
if(isset($_SESSION['autenticado']) && $_SESSION['autenticado']=='true')
    $autenticado = true;
function upload_img_dir(){
	// Envio para o diretorio
	$target_dir = "uploads/";
	global $target_file;
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Verifica se a imagem é realmente uma imagem ou uma imitação
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        echo "<br>Arquivo é uma imagem - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        echo "<br>Arquivo não é uma imagem.";
	        $uploadOk = 0;
	    }
	}

	// Verifica se o arquivo já existe
	while (file_exists($target_file)) {
	    $target_file =  $target_dir.md5(time()).'.jpg';
	}

	// Verifica o tamanho do arquivo
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	    echo "<br>Desculpe, arquivo grande demais.";
	    $uploadOk = 0;
	}

	// Permita apenas alguns formatos de arquivo
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "<br>Desculpe, somente arquivos tipo JPG, JPEG, PNG & GIF são aceitos.";
	    $uploadOk = 0;
	}

	// Se $uploadOk for 0, então ocorreu um erro
	if ($uploadOk == 0) {
	    echo "<br>Desculpe, ocorreu um erro no envio do arquivo.";
	// Senão, tudo correu bem
	} else {
		// move o arquivo para o destino definitivo
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        echo "<br>O arquivo ". basename( $_FILES["fileToUpload"]["name"]). " foi recebido.";
	    } else {
	            echo "<br>Desculpe, ocorreu um erro no envio do arquivo.";
	    }
	}
}
function upload_img_bd($filename,$conexao){
	$stmt = $conexao->prepare("UPDATE jogador SET imagem_j = ? WHERE nome_j = ?");
	$stmt->bindParam(1,$filename);
	$stmt->bindParam(2,$_SESSION['username']);
	$stmt->execute();
}

if($autenticado) {
	if($metodo == "POST" && $_POST['submit'] == "Enviar Imagem"){
		upload_img_dir();
		upload_img_bd($target_file,$conexao);
	}

	?>
	
	<form method="post" enctype="multipart/form-data">
	    Selecione uma imagem para seu perfil:<br>
	    <input type="file" name="fileToUpload" id="fileToUpload"><br>
	    <input type="submit" value="Enviar Imagem" name="submit">
	</form>
	<?php
	require("_home.php");

}
?>
</div>