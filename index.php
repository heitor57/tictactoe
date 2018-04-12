
<?php
    // numero de vitorias necessarios para conseguir as medalhas
    $medalhabronze = 2; 
    $medalhaprata = 4; 
    $medalhaouro = 6; 
    function perfil($username){
        global $conexao;
        ?>
        <div style="box-shadow: 10px 10px 5px #888888;text-align: center;border-style: outset; border-width: 10px;width: 150px;">
            <img style="" src=<?=perfil_img_str($conexao,$username)?> alt="Sem suporte a imagem" height="150" width="150">
            <h1 style="font-size:15px ;word-wrap: break-word"><?= $username ?></h1>
        </div>
        <?php
    }
    function NumVitorias(){
        global $conexao;
        $stmt = $conexao->prepare("SELECT * FROM partida WHERE (p_j1=? OR p_j2=?) AND p_vencedor = ?");
        $stmt->bindParam(1,$_SESSION['username']);
        $stmt->bindParam(2,$_SESSION['username']);
        $stmt->bindParam(3,$_SESSION['username']);              
        $stmt->execute();
        $result = $stmt->fetchAll();
        return count($result);
    }
    function NumPartidas(){
        global $conexao;
        $stmt = $conexao->prepare("SELECT * FROM partida WHERE (p_j1=? OR p_j2=?) AND p_ativa = 0");
        $stmt->bindParam(1,$_SESSION['username']);
        $stmt->bindParam(2,$_SESSION['username']);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return count($result);
    }
    function NumEmpates(){
        global $conexao;
        $stmt = $conexao->prepare("SELECT * FROM partida WHERE (p_j1=? OR p_j2=?) AND (p_ativa = 0 AND p_vencedor IS NULL)");
        $stmt->bindParam(1,$_SESSION['username']);
        $stmt->bindParam(2,$_SESSION['username']);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return count($result);
    }
    function perfil_img_str($conexao,$username){
        $stmt = $conexao->prepare("SELECT imagem_j FROM jogador WHERE nome_j = ?");
        $stmt->bindParam(1,$username);
        $stmt->execute();
        return $stmt->fetch()[0];
    }
    $metodo  = $_SERVER['REQUEST_METHOD'];
    require("_con.php");
    $titulo = "Bem vindo a Página Inicial";
    require("_header.php");

    session_start();
    if(isset($_POST['partida']))
        $_SESSION['partida'] = $_POST['partida'];
    if(isset($_POST['home']))
        unset($_SESSION['partida']);

    $autenticado = false;
    if(isset($_SESSION['autenticado']) && $_SESSION['autenticado']=='true')
        $autenticado = true;

    if($autenticado) { ?>
        <div>
        <div style="display: inline-block;width: 250px;">
            <form style="float: left;" method=POST action="ranking.php"><input type="submit" name="submit" value="Ranking"></form>
            <form style="float: left;" method=POST action="configurar.php"><input type="submit" name="submit" value="Configurações"></form>
            <form style="float: left;" method=POST action="logout.php"><input type="submit" name="submit" value="Sair"></form><br><br>
<?php
        
        if( !isset($_SESSION['partida'])){
            // Menu para desafiar jogadores
            perfil($_SESSION['username']);
            ?> 
            <form method="POST" action= "">
            <h3>Desafiar jogador</h3>
            <input type="text" name="j1" value = ""><br>
            <br>
            <input type="submit" name="jogadorenvio" value = "Desafiar">
            <br><br>
            </form>
            </div>
            
            <div style="display: inline-block;vertical-align: top;">
                <h1>Mural de Medalhas</h1>
                
                <?php
                // Medalhas
                $numero_de_vitorias= NumVitorias();
                $tempmedalhas = "";
                if($numero_de_vitorias >= $medalhabronze){
                    $tempmedalhas .= '<img style="box-shadow: 10px 10px 5px #888888;margin-left: 20px;" src="uploads/bronze.png" alt="Sem suporte a imagem" height="150" width="150">';
                    if($numero_de_vitorias >= $medalhaprata){
                        $tempmedalhas .= '<img style="box-shadow: 10px 10px 5px #888888;margin-left: 20px;" src="uploads/silver.png" alt="Sem suporte a imagem" height="200" width="200">';
                        if($numero_de_vitorias >= $medalhaouro){
                            $tempmedalhas .= '<img style="box-shadow: 10px 10px 5px #888888;margin-left: 20px;" src="uploads/gold.png" alt="Sem suporte a imagem" height="250" width="250">';
                        }
                    }
                }
                echo $tempmedalhas;
                ?>
            </div>
        </div>
            <?php

           
            if($metodo == "POST"){
                //Negaçao de um desafio
                if(isset($_POST["recusar"])){
                    $stmt = $conexao->prepare("DELETE FROM desafio WHERE d_j1=? AND d_j2=? ");
                    $stmt->bindParam(1,$_POST['j1']);
                    $stmt->bindParam(2,$_SESSION['username']);
                    $stmt->execute();
                // Aceitação de um desafio
                }else if(isset($_POST["aceitar"])){
                    // Aceitação de desafio e criação de partida
                    $stmt = $conexao->prepare("UPDATE desafio SET d_j2_a=1 WHERE d_j1=? AND d_j2=?");
                    $stmt->bindParam(1,$_POST['j1']);
                    $stmt->bindParam(2,$_SESSION['username']);
                    $stmt->execute();
                    $stmt = $conexao->prepare("SELECT * FROM partida WHERE ((p_j1 = ? AND p_j2 = ?) OR (p_j1 = ? AND p_j2 = ?)) AND p_ativa = 1");
                    $stmt->bindParam(1,$_POST['j1']);
                    $stmt->bindParam(2,$_SESSION['username']);
		    $stmt->bindParam(3,$_SESSION['username']);
                    $stmt->bindParam(4,$_POST['j1']);
		    $stmt->execute();
                    $result= $stmt->fetchAll();
                    if(count($result)==0){
                        $stmt = $conexao->prepare("INSERT INTO `partida`(`p_j1`, `p_j2`, `p_j1s`, `p_j2s`, `p_ativa`, `p_vez`) VALUES (?,?,?,?,?,?)");
                        $stmt->bindParam(1,$_POST['j1']);
                        $stmt->bindParam(2,$_SESSION['username']);
                        $x = array('X','O');
                        $random = rand(0,1);
                        $j1s = $x[$random];
                        if($random == 1){
                            $j2s = $x[$random -1];
                        }else{
                            $j2s = $x[$random +1];
                        }
                        $stmt->bindParam(3,$j1s);
                        $stmt->bindParam(4,$j2s);
                        $stmt->bindValue(5,1);
                        $stmt->bindValue(6,rand(1,2));
                        $stmt->execute();
                    }
                }else if(isset($_POST["jogadorenvio"]) && !empty($_POST["j1"]) && $_POST['j1']!=$_SESSION['username']){
                    // Solicitação de desafio
                    try{
                        // Verifica se não existe desafio já feito
                        $stmt = $conexao->prepare("SELECT * FROM desafio WHERE (d_j1=? AND d_j2=?) OR (d_j1=? AND d_j2=?) ");
                        $stmt->bindParam(1,$_SESSION['username']);
                        $stmt->bindParam(2,$_POST['j1']);
                        $stmt->bindParam(3,$_POST['j1']);
                        $stmt->bindParam(4,$_SESSION['username']);
                        $stmt->execute();
                        $result= $stmt->fetchAll();
                        if(count($result)==0){
                            $stmt = $conexao->prepare("INSERT INTO desafio(d_j1,d_j2, d_j1_a) VALUES (?,?,?) ");
                            $stmt->bindParam(1, $_SESSION['username']);
                            $stmt->bindParam(2,$_POST['j1']);
                            $stmt->bindValue(3,1);
                            $stmt->execute();
                        }
                    }catch(Exception $e){
                    
                    }
                }else if(isset($_POST["abandonar"])){
                    // Abandonar partida
                    $winner = "";
                    if($_POST['partidaj1'] == $_SESSION['username'])
                        $winner = $_POST['partidaj2'];
                    else
                        $winner = $_POST['partidaj1'];

                    $stmt = $conexao->prepare("UPDATE partida SET p_ativa = 0, p_vencedor = ? WHERE p_id_partida=?");
                    $stmt->bindParam(1,$winner);
                    $stmt->bindParam(2,$_POST['partidaid']);

                    $stmt->execute();
                    $stmt = $conexao->prepare("DELETE FROM desafio WHERE d_j1=? AND d_j2=? ");
                    $stmt->bindParam(1,$_POST['partidaj1']);
                    $stmt->bindParam(2,$_POST['partidaj2']);
                    $stmt->execute();
                }
            }
            // Estatisticas
            require("_statics.php");
            // ----------------
            //Lista de desafiantes
            $stmt = $conexao->prepare("SELECT * FROM desafio WHERE d_j2 = ? AND d_j2_a = 0");
            $stmt->bindParam( 1, $_SESSION['username']);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if(count($result)>0)
                echo "<div style='text-align: left;display: inline-block;vertical-align: top;margin-right: 4%;text-align: center;' ><h2>Desafiantes</h2><div class='tabela_rel'>";
            foreach($result as $result_simple){
                echo "<br><b>".$result_simple['d_j1']."</b><form method=POST action=''><input type=hidden name=j1 value=".$result_simple['d_j1']." ><input type=submit name='aceitar' value='Aceitar'><input type=submit name='recusar' value='Recusar'></form>";
            }
            echo "</div></div>";
            // Lista de jogos em andamento
            $stmt = $conexao->prepare("SELECT * FROM partida WHERE p_ativa=1 AND (p_j1 = ? OR p_j2 = ?)");
            $stmt->bindParam(1,$_SESSION['username']);
            $stmt->bindParam(2,$_SESSION['username']);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if(count($result)>0)
                echo "<div style='text-align:left;display: inline-block;vertical-align: top; text-align: center;'><h2>Jogos</h2><div class='tabela_rel'>";
            foreach($result as $result_simple){
                echo "<br><b>".(($result_simple['p_j1'] == $_SESSION['username']) ? $result_simple['p_j2'] : $result_simple['p_j1'])."</b>
                <form method=POST action=''>
                    <input type=hidden name=partidaid value=".$result_simple['p_id_partida'].">
                    <input type=submit name='partida' value='Entrar na partida'>
                </form>
                <form method = POST action=''>
                    <input type=hidden name=partidaid value = ".$result_simple['p_id_partida']." > 
                    <input type=hidden name=partidaj1 value= ".$result_simple['p_j1']." >
                    <input type=hidden name=partidaj2 value = ".$result_simple['p_j2']." > 
                    <input type=submit name=abandonar value=Abandonar>
                </form>";
            }
            echo '</div></div><br><form method="POST">
                 <input type="submit" name="atualizar" value="Atualizar">
                 </form>';
        }else{
            // Tela de jogo
            require("jogo.php");
            require("_home.php");
        }  

	} else { ?>
        <h1 style="text-color: red;">Você não está autenticado!</h1>
        <h2>Faça seu <a href="login.php">login</a></h2>
<?php	} 

    require("_footer.php");
?>



