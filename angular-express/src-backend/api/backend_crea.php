<?php
/* CODED BY Salvatore Pizzimento FOR Angular-Express Project */

require_once '_db_mysql.php';

//viene decodificato il file JSON
$json = file_get_contents('php://input');
$params = json_decode($json);

//viene creata la prenotazione con gli input forniti dal JSON decodificato
$stmt = $db->prepare("INSERT INTO prenotazioni (etichetta, inizio, fine, id_aula) VALUES (:etichetta, :inizio, :fine, :aula)");
$stmt->bindParam(':inizio', $params->start);
$stmt->bindParam(':fine', $params->end);
$stmt->bindParam(':etichetta', $params->text);
$stmt->bindParam(':aula', $params->resource);
$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Created with id: '.$db->lastInsertId();
$response->id = $db->lastInsertId();

//il risultato viene convertito in JSON
header('Content-Type: application/json');
echo json_encode($response);

?>
