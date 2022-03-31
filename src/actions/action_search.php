<?php

session_start();

include_once '../database/connection.php';
include_once '../database/search.php';

$animal_array = [];
$animal_array_aux = [];
$search = $_POST['search'];
$words = explode(' ', $search);
$db = getDB();
$aux = 0;

foreach ($words as $word) {
  $results = search_word($db, $word);
  foreach ($results as $result) {
    $animal_array_aux[] = $result;
  }
  if ($aux == 0) {
    $animal_array = $animal_array_aux;
  }
  if ($aux == 0) {
    $aux = 1;
  }

  var_dump($animal_array, $animal_array_aux);

  $animal_array = array_intersect($animal_array, $animal_array_aux);
  unset($animal_array_aux);
}
$_SESSION['animal_array'] = $animal_array;

die();

header('Location: ../search_cards.php');

?>