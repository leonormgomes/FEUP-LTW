<?php

include_once '../../database/search.php';

function search_parameter($db, $table, $parameters, $search)
{
  $array = [];
  $words = explode(' ', $search);

  foreach ($words as $word) {
    $results = search_word($db, $table, $parameters, $word);

    $array = array_merge($array, $results);
  }

  $array = array_map(
    'unserialize',
    array_unique(array_map('serialize', $array))
  );

  return $array;
}