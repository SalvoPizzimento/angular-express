<?php
/* CODED BY Salvatore Pizzimento FOR Angular-Express Project */

require_once '_db_mysql.php';

//vengono selezionate le prenotazioni con input di orario corretti
$stmt = $db->prepare('SELECT * FROM prenotazioni WHERE NOT ((fine <= :inizio) OR (inizio >= :fine))');
$stmt->bindParam(':inizio', $_GET["from"]);
$stmt->bindParam(':fine', $_GET["to"]);
$stmt->execute();
$result = $stmt->fetchAll();

class Prenotazione {}
$prenotazioni = array();

//per ogni prenotazione vengono assegnati i corretti attributi
foreach($result as $row) {
  $p = new Prenotazione();
  $p->id = $row['id'];
  $p->text = $row['etichetta'];
  $p->start = $row['inizio'];
  $p->end = $row['fine'];
  $p->resource = $row['id_aula'];
  $prenotazioni[] = $p;
}

//il risultato viene convertito in JSON
header('Content-Type: application/json');
echo json_encode($prenotazioni);

?>
