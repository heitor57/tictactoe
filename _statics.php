<?php
    
    $numero_de_partidas= NumPartidas();
    $empates= NumEmpates();
    $numero_de_vitorias= NumVitorias();

    if($numero_de_partidas > 0)
    echo "Taxa de vitoria: ".number_format($numero_de_vitorias*100/$numero_de_partidas, 2)."&#37;<br>
    Partidas jogadas: ".$numero_de_partidas."<br>
    Empates: ".$empates."<br>
    Derrotas: ".($numero_de_partidas-$empates-$numero_de_vitorias)."<br>
    Vitorias: ".$numero_de_vitorias."<br><br>";    
?>