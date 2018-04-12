
<?php
//function update(){
  //  global $j1,$j2,$j1s,$j2s,$vez,$id_partida,$tabuleiro,$j_num,$j_s;
    
//}

function setUpdate($vez,$j_num){
    // So vai ter update quando a vez não for dele
    if($vez != $j_num)
    echo '<meta http-equiv="refresh" content="2" >';
}
function UpdateValuesGame(){
    global $conexao,$tabuleiro,$j1,$j2,$j1s,$j2s,$vez,$result,$id_partida;
    $stmt = $conexao->prepare("SELECT * FROM partida WHERE p_id_partida = ?");
    $stmt->bindParam(1,$_SESSION['partidaid']);
    $stmt->execute();
    $result = $stmt->fetch();
    $tabuleiro = array(array($result['p_a1'],$result['p_a2'],$result['p_a3']),array($result['p_b1'],$result['p_b2'],$result['p_b3']),array($result['p_c1'],$result['p_c2'],$result['p_c3']));
    $j1=$result['p_j1'];
    $j2=$result['p_j2'];
    $j1s=$result['p_j1s'];
    $j2s=$result['p_j2s'];
    $vez=$result['p_vez'];
    $id_partida=$result['p_id_partida'];

}

function fulltable($conexao,$id_partida){
    $stmt = $conexao->prepare("SELECT * FROM partida WHERE p_id_partida = ? AND p_a1!='' AND p_a2!='' AND p_a3!='' AND p_b1!='' AND p_b2!='' AND p_b3!='' AND p_c1!='' AND p_c2!='' AND p_c3!=''");
    $stmt->bindParam(1,$id_partida);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if(count($result) > 0)
        return true;
    return false;
}

if(isset($_POST['partidaid'])){
    $_SESSION['partidaid'] = $_POST['partidaid'];
}
$tabuleiro_pontos = array(array('p_a1','p_a2','p_a3'),array('p_b1','p_b2','p_b3'),array('p_c1','p_c2','p_c3'));
UpdateValuesGame();

if($j1 == $_SESSION['username'])
    $j_num = 1;
else
    $j_num = 2;
$j_s = $result['p_j'.$j_num.'s'];
// -----------------------------
if($metodo == "POST"){
    if(isset($_POST['linha']) && isset($_POST['coluna'])  && $vez == $j_num && empty($tabuleiro[$_POST['linha']][$_POST['coluna']])){
        $string ="UPDATE partida SET ";
        $string = $string.$tabuleiro_pontos[$_POST['linha']][$_POST['coluna']]." = ?, p_vez=? WHERE p_id_partida = ?";
        $stmt = $conexao->prepare($string);
        $stmt->bindParam(1,$j_s);
        if($vez == 1)
            $stmt->bindValue(2,2);
        else
            $stmt->bindValue(2,1);
        $stmt->bindValue(3,$id_partida);
        $stmt->execute();
    }
}
UpdateValuesGame();
setUpdate($vez,$j_num);  
$acabou = 0;
// primeira verificação de quem ganhou
for($contador  = 0 ;$contador <=2; $contador++){
    $contador2_interno_j1s = 0;
    $contador2_interno_j2s = 0;
    for($contador2 = 0 ; $contador2 <=2 ; $contador2++){
        if($tabuleiro[$contador][$contador2] == $j1s){
            $contador2_interno_j1s++;
        }
        if($tabuleiro[$contador][$contador2] == $j2s){
            $contador2_interno_j2s++;
        }

    }
    if($contador2_interno_j1s==3){
        $acabou = 1;
        $jogadorganhador=$j1;
    }elseif($contador2_interno_j2s==3){
        $acabou = 1;
        $jogadorganhador=$j2;
    }
}

// segunda verificação de quem ganhou
for($contador  = 0 ;$contador <=2; $contador++){
    $contador2_interno_j1s = 0;
    $contador2_interno_j2s = 0;
    for($contador2 = 0 ; $contador2 <=2 ; $contador2++){
        if($tabuleiro[$contador2][$contador] == $j1s){
            $contador2_interno_j1s++;
        }
        if($tabuleiro[$contador2][$contador] == $j2s){
            $contador2_interno_j2s++;
        }
    }
    if($contador2_interno_j1s==3){
        $acabou = 1;
        $jogadorganhador=$j1;
    }elseif($contador2_interno_j2s==3){
        $acabou = 1;
        $jogadorganhador=$j2;
    }
}


// terceira verificação de quem ganhou
if($tabuleiro[0][0] == $j1s && $tabuleiro[1][1] == $j1s && $tabuleiro[2][2] == $j1s)
{
    $acabou = 1;
    $jogadorganhador=$j1;
}elseif($tabuleiro[0][0] == $j2s && $tabuleiro[1][1] == $j2s && $tabuleiro[2][2] == $j2s){
        $acabou = 1;
        $jogadorganhador=$j2;
}

// quarta verificação de quem ganhou
if($tabuleiro[0][2] == $j1s && $tabuleiro[1][1] == $j1s && $tabuleiro[2][0] == $j1s)
{
    $acabou = 1;
    $jogadorganhador=$j1;
}elseif($tabuleiro[0][2] == $j2s && $tabuleiro[1][1] == $j2s && $tabuleiro[2][0] == $j2s){
        $acabou = 1;
        $jogadorganhador=$j2;
}
// Verificação de empate
if($acabou == 1){
    echo "<h3> O jogador ".$jogadorganhador." ganhou o jogo.</h3>";
    $stmt = $conexao->prepare("UPDATE partida SET p_vencedor=?, p_ativa =0  WHERE p_id_partida = ? ");
    $stmt->bindParam(1,$jogadorganhador);
    $stmt->bindValue(2,$id_partida);
    $stmt->execute();
    $acabou = 1;
    $stmt = $conexao->prepare("DELETE FROM desafio WHERE d_j1=? AND d_j2=? ");
    $stmt->bindParam(1,$j1);
    $stmt->bindParam(2,$j2);
    $stmt->execute();
}elseif(fulltable($conexao,$id_partida)){
    echo "<h3>Velha...</h3>";
    $stmt = $conexao->prepare("DELETE FROM desafio WHERE d_j1=? AND d_j2=? ");
    $stmt->bindParam(1,$j1);
    $stmt->bindParam(2,$j2);
    $stmt->execute();
    $stmt = $conexao->prepare("UPDATE partida SET p_ativa=0 WHERE p_id_partida = ?");
    $stmt->bindValue(1,$id_partida);
    $stmt->execute();
    $acabou = 1;
}

UpdateValuesGame();
// Mostrando os dois perfis dos combatentes
echo "<div style= 'display:flex;vertical-align:top;margin-bottom: 30px;'>";
if($vez == 1){
    echo "<div style='background:green;'>";
}else{
    echo "<div>";
}
perfil($j1);
echo "</div>";
echo '<img src="uploads/versus.png" alt="Sem suporte a imagem" height="150" width="150">';
if($vez == 2){
    echo "<div style='background:green;'>";
}else{
    echo "<div>";
}
perfil($j2);
// ---- Finaliza codigo dos perfis
// Imprime tabuleiro
echo "</div></div>";
echo "
<table style='display: inline;'>
  <tr>
    <td>".$tabuleiro[0][0]."</td>
    <td>".$tabuleiro[0][1]."</td>
    <td>".$tabuleiro[0][2]."</td>
  </tr>
  <tr>
    <td>".$tabuleiro[1][0]."</td>
    <td>".$tabuleiro[1][1]."</td>
    <td>".$tabuleiro[1][2]."</td>
  </tr>
  <tr>
    <td>".$tabuleiro[2][0]."</td>
    <td>".$tabuleiro[2][1]."</td>
    <td>".$tabuleiro[2][2]."</td>
  </tr>
</table> ";
// ----
?>
    
<form method="POST" >
	<br><br>
    <div style="">
	<?php
		if($vez == 1){
			echo '
            <div style = "font-weight: bold;">'.$j1.' - '. $j1s .'</div><br>
            <div> '.$j2.' - '. $j2s .'</div><br>';
		}else{
			echo '
            <div>'.$j1.' - '. $j1s .'</div><br>
            <div style = "font-weight: bold;"> '.$j2.' - '. $j2s.'</div><br>';
		}
	?>
    </div>
	<h3>Coluna</h3>
	<input type="range" name="coluna" min="0" max="2"><br><br>
	0 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2
	<br>
	<h3>Linha</h3>
	<input type="range" name="linha" min="0" max="2"><br><br>
	0 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2
	<br>
   
	<?php
	if(isset($acabou) && $acabou == 1){
        
    }else{
        echo '<input type="submit" name="partida" value = "Enviar">';
    }

	?>
</form>
