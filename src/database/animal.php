<?php

function insertAnimal(
  $db,
  $name,
  $photo,
  $description,
  $location,
  $age,
  $size,
  $species,
  $listed
) {
  $stmt = $db->prepare(
    'INSERT INTO Animal(name, photo, description, location, age, size, species, listed_for_adoption)
        VALUES(?, ?, ?, ?, ?, ?, ?, ?)'
  );
  if (!$stmt) {
    return false;
  }

  return $stmt->execute([
    $name,
    $photo,
    $description,
    $location,
    $age,
    $size,
    $species,
    $listed,
  ]);
}

function lastInsertedAnimalId($db)
{
  $stmt = $db->prepare('SELECT id FROM Animal ORDER BY id DESC LIMIT 1');
  if (!$stmt) {
    return false;
  }

  $stmt->execute();

  return $stmt->fetch()['id'];
}

function insertAnimalOwner($db, $owner, $animal)
{
  $stmt = $db->prepare(
    'INSERT INTO Owns(user, animal)
        VALUES(?, ?)'
  );
  if (!$stmt) {
    return false;
  }

  return $stmt->execute([$owner, $animal]);
}