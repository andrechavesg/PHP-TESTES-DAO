<?php
require_once("autoload.php");
use src\br\com\caelum\leilao\Factory\ConnectionFactory;


function criaDatabase(){
    try {
    	$db = ConnectionFactory::getDatabase();
    
        $con = ConnectionFactory::getConnectionWithoutDb(); 

        $criou = $con->exec("CREATE DATABASE `$db`"); 
        
		echo $criou ? "Database ".$db." criada com sucesso!\n" : "Database ".$db." já existe\n";
    } catch (PDOException $e) {
        die("DB ERROR: ". $e->getMessage());
    }
}
function criaTabelas() {
	$db = ConnectionFactory::getDatabase();
    
    $con = ConnectionFactory::getConnectionWithoutDb(); 

	$sql = "use ".$db;
    $con->exec($sql);

	$sql = "CREATE TABLE IF NOT EXISTS Usuario (
                id int(11) AUTO_INCREMENT PRIMARY KEY,
                nome varchar(255) NOT NULL,
                email varchar(255) NOT NULL)";
    $con->exec($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS Leilao (
                id int(11) AUTO_INCREMENT PRIMARY KEY,
                nome varchar(255) NOT NULL,
                valorInicial double NOT NULL,
                dono int NOT NULL,
                dataAbertura date NOT NULL,
                usado boolean,
                encerrado boolean)";
    $con->exec($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS Lance (
                id int(11) AUTO_INCREMENT PRIMARY KEY,
                usuario int NOT NULL,
                data date NOT NULL,
                valor double NOT NULL,
                leilao int NOT NULL)";
    $con->exec($sql);
    
    echo "Tabelas cridas com sucesso!\n";
}

criaDatabase();
criaTabelas();