<?php

function helper($param)
{
  return $param . ' LIKE ?';
}

function search_word($db, $table, $parameters, $word)
{
  $search = implode(' or ', array_map('helper', $parameters));

  if ($table !== 'animal' && $table !== 'user') {
    die();
  }

  $stmt = $db->prepare('SELECT * FROM ' . $table . ' WHERE ' . $search);

  if (!$stmt) {
    return false;
  }

  $word = '%' . $word . '%';

  $words = array_fill(0, count($parameters), $word);

  $stmt->execute(array_merge([], $words));

  $result = $stmt->fetchAll();
  return $result;
}