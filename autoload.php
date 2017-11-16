<?php

function carregaClasse($nome)
{
    $ds = DIRECTORY_SEPARATOR;
    
    $caminho = __DIR__ . $ds . str_replace("\\", $ds, "$nome.php");
    
    if (file_exists($caminho)) {
        include_once "$caminho";
    } else {
        die("o arquivo " . $caminho . " não existe");
    }
}

spl_autoload_register("carregaClasse");