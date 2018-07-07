<?php
/* CODED BY Salvatore Pizzimento FOR Angular-Express Project */

//configurazioni da settare per accedere a MySQL
$host = "127.0.0.1";
$port = 3306;
$username = "root";
$password = "";
$database = "aulario";

date_default_timezone_set("UTC");

$db = new PDO("mysql:host=$host;port=$port",
               $username,
               $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//se il database non esiste viene creato
$db->exec("CREATE DATABASE IF NOT EXISTS `$database`");
$db->exec("use `$database`");

//funzione per controllare se esiste la tabella in input
function tableExists($dbh, $id) {
    $results = $dbh->query("SHOW TABLES LIKE '$id'");
    if(!$results) {
        return false;
    }
    if($results->rowCount() > 0) { 
        return true;
    }
    return false;
}

$exists = tableExists($db, "prenotazioni");

if (!$exists) {
    //viene creata la tabella prenotazioni
    $db->exec("CREATE TABLE IF NOT EXISTS prenotazioni (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        etichetta TEXT,
        inizio DATETIME,
        fine DATETIME,
        id_aula VARCHAR(30))");

    //viene creata la tabella aule
    $db->exec("CREATE TABLE IF NOT EXISTS aule (
        id INTEGER  PRIMARY KEY AUTO_INCREMENT NOT NULL,
        nome VARCHAR(200)  NULL)");

    //viene creato un array di aule
    $items = array(
        array('nome' => 'Aula 1'),
        array('nome' => 'Aula 2'),
        array('nome' => 'Aula 3'),
        array('nome' => 'Aula 4'),
        array('nome' => 'Aula 22'),
        array('nome' => 'Aula 23'),
        array('nome' => 'Aula 24'),
        array('nome' => 'Aula 25'),
        array('nome' => 'Aula 36'),
        array('nome' => 'Aula 42'),
        array('nome' => 'Laboratorio 126'),
        array('nome' => 'Laboratorio 145'),
    );

    //viene popolata la tabella aule
    $insert = "INSERT INTO aule (nome) VALUES (:nome)";
    $stmt = $db->prepare($insert);
    $stmt->bindParam(':nome', $name);
    foreach ($items as $m) {
        $name = $m['nome'];
        $stmt->execute();
    }
}

?>