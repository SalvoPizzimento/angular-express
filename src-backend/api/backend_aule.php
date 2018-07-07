<?php
/* CODED BY Salvatore Pizzimento FOR Angular-Express Project */

require_once '_db_mysql.php';

class Aula {}

//viene preparato l'elenco delle aule da visualizzare
$stmt = $db->prepare('SELECT * FROM aule ORDER BY nome');
$stmt->execute();
$scheduler_aule = $stmt->fetchAll();  

$aule = array();

//per ogni elemento dell'elenco viene creata un'aula con id e nome
foreach($scheduler_aule as $aula) {
  $a = new Aula();
  $a->id = $aula['id'];
  $a->name = $aula['nome'];
  $aule[] = $a;
}

//il risultato viene convertito in JSON
header('Content-Type: application/json');
echo json_encode($aule);

?>
