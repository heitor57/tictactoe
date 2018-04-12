<form action="index.php">
	<input type="submit" value="Pagina inicial">
</form>
<?php
function NumVitorias($username){
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM partida WHERE (p_j1=? OR p_j2=?) AND p_vencedor = ?");
    $stmt->bindParam(1,$username);
    $stmt->bindParam(2,$username);
    $stmt->bindParam(3,$username);              
    $stmt->execute();
    $result = $stmt->fetchAll();
    return count($result);
}
function NumPartidas($username){
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM partida WHERE (p_j1=? OR p_j2=?) AND p_ativa = 0");
    $stmt->bindParam(1,$username);
    $stmt->bindParam(2,$username);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return count($result);
}
function NumEmpates($username){
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM partida WHERE (p_j1=? OR p_j2=?) AND (p_ativa = 0 AND p_vencedor IS NULL)");
    $stmt->bindParam(1,$username);
    $stmt->bindParam(2,$username);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return count($result);
}
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Ranking</title>
        <style type="text/css">
            .fundocinza{
               background:grey;
            }
            .fundooutro{
               background:#7a7a52;
            }
            .espacoocupado{
                width: 100%;
                height: 100px;
                padding-left: 20px;
            }
	    </style>   
	</head>
<body>


<?php
    

require("_con.php");
$stmt = $conexao->query("SELECT * FROM jogador");
$stmt->execute();
$results = $stmt->fetchAll();
$i=0;
    
$lista_ordenada = array();
// Pega uma lista ordenada
foreach($results as $result){
    $numero_de_vitorias = NumVitorias($result['nome_j']);
    $empates = NumEmpates($result['nome_j']);
    $numero_de_partidas= NumPartidas($result['nome_j']);
    if($numero_de_partidas>0)
    $taxa_de_vitoria =$numero_de_vitorias*100/$numero_de_partidas;
    else
    $taxa_de_vitoria = 0;
    $lista_ordenada[$result['nome_j']]=array(number_format($taxa_de_vitoria, 2),$result['imagem_j']); 
    
}
//Ordena todos pela taxa de vitoria
arsort($lista_ordenada);

for ($i=0;$i<count($results);$i++){
   $results[$i]['nome_j'] = array_keys($lista_ordenada)[$i];
    $results[$i]['imagem_j'] = $lista_ordenada[array_keys($lista_ordenada)[$i]][1];
}
// Imprime na tela
foreach($results as $result){
    
    
    $numero_de_vitorias = NumVitorias($result['nome_j']);
    $empates = NumEmpates($result['nome_j']);
    $numero_de_partidas= NumPartidas($result['nome_j']);
    
    
    echo "<div class='".($i%2 !=0 ? 'fundocinza' : 'fundooutro' ) ." espacoocupado'>";
    echo '<img style="display:inline-block;" src="'.$result['imagem_j'] .'" alt="Sem suporte a imagem" height="100" width="100">';
    echo "<div style='margin-left:50px;margin-bottom:90px;display:inline-block;vertical-align:middle;'><div style= 'display:inline-block;'>".$result['nome_j']."</div>";
    if($numero_de_partidas > 0){
        $taxa_de_vitoria =$numero_de_vitorias*100/$numero_de_partidas;
        $derrotas = ($numero_de_partidas-$empates-$numero_de_vitorias);
        echo "<div style= 'display:inline-block;margin-left:50px;'>Vitorias: ".$numero_de_vitorias." Empates: ".$empates." Derrotas: ".$derrotas." Partidas: ".$numero_de_partidas." Taxa de vitoria: ".number_format($taxa_de_vitoria, 2)."&#37;</div>
        </div>";
    }else{
        echo "<div style= 'display:inline-block;margin-left:50px;'> Nenhuma partida jogada </div></div>";
    }
    echo "</div><br>";
    
    $i++;
}

?>

