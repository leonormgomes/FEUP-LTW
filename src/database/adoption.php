<?php

function insertAdoptionProposal($db, $animal, $email)
{
  $stmt = $db->prepare(
    'INSERT INTO AdoptionProposal(status, animal, person)
        VALUES("WAITING", ?, ?)'
  );
  if (!$stmt) {
    return false;
  }

  return $stmt->execute([$animal, $email]);
}

function updateAdoptionProposal($db, $animal, $email, $status)
{
  $stmt = $db->prepare(
    'UPDATE AdoptionProposal SET status = ? WHERE animal = ? AND person = ?'
  );
  if (!$stmt) {
    return false;
  }

  return $stmt->execute([$status, $animal, $email]);
}

function getListed($db, $email)
{
  $stmt = $db->prepare(
    'SELECT Owns.animal, id, name, photo, description, location, age, size, species, listed_for_adoption
        FROM Owns, Animal
        WHERE user = ? AND Animal.id = Owns.animal AND listed_for_adoption = 1'
  );

  if (!$stmt) {
    return false;
  }

  $stmt->execute([$email]);
  $results = $stmt->fetchAll();

  foreach ($results as $key => $value)
    $results[$key]['proposed'] = (hasProposes($db, $value['id'])) ? 1 : 0;

  return $results;
}

function getProposals($db, $email)
{
  $stmt = $db->prepare(
    'SELECT AdoptionProposal.animal, status, id, name, photo, description, location, age, size, species, listed_for_adoption
        FROM AdoptionProposal, Animal
        WHERE person = ? AND Animal.id = AdoptionProposal.animal'
  );

  if (!$stmt) {
    return false;
  }

  $stmt->execute([$email]);
  return $stmt->fetchAll();
}

function getAnimalActiveProposals($db, $animal_id)
{
  $stmt = $db->prepare(
    'SELECT User.username, User.profile_picture, User.email
        FROM AdoptionProposal, Animal, User
        WHERE AdoptionProposal.status = "WAITING" AND Animal.id = AdoptionProposal.animal AND AdoptionProposal.animal = ? AND AdoptionProposal.person = User.email'
  );

  if (!$stmt) {
    return false;
  }

  $stmt->execute([$animal_id]);
  return $stmt->fetchAll();
}

function isProposed($db, $email, $animal_id)
{
  $stmt = $db->prepare(
    'SELECT *
    FROM AdoptionProposal
    WHERE person = ? AND animal = ?'
  );

  if (!$stmt) {
    return false;
  }

  $stmt->execute(array($email, $animal_id));
  return $stmt->fetch();
}

function hasProposes($db, $animal_id)
{
  $stmt = $db->prepare(
    'SELECT *
      FROM AdoptionProposal
      WHERE animal = ? AND AdoptionProposal.status = "WAITING"'
  );

  if (!$stmt) {
    return false;
  }

  $stmt->execute([$animal_id]);
  return $stmt->fetch();
}
