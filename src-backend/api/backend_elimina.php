<?php
/* CODED BY Salvatore Pizzimento FOR Angular-Express Project */

require_once '_db_mysql.php';

//viene decodificato il file JSON
$json = file_get_contents('php://input');
$params = json_decode($json);

//viene eliminata la prenotazione con l'id fornito dal JSON decodificato
$stmt = $db->prepare("DELETE FROM prenotazioni WHERE id = :id");
$stmt->bindParam(':id', $params->id);
$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Delete successful';

//il risultato viene convertito in JSON
header('Content-Type: application/json');
echo json_encode($response);

?>
