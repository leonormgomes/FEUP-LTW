<?php

function getAnimalById($db, $animal_id)
{
  $stmt = $db->prepare(
    'SELECT *
    FROM Animal
    WHERE id = ?
    LIMIT 1'
  );

  $stmt->execute([$animal_id]);

  return $stmt->fetch();
}

function getAnimalOwner($db, $animal_id)
{
  $stmt = $db->prepare(
    'SELECT username,email, first_name, last_name, phone_number, User.description as description, profile_picture, cover_picture, User.location as location, birth_date
    FROM Animal
    INNER JOIN Owns on Owns.Animal=Animal.id
    INNER JOIN User on Owns.User=User.email
    WHERE Animal.id = ?
    LIMIT 1'
  );

  $stmt->execute([$animal_id]);

  return $stmt->fetch();
}

function getQuestionsOfAnimal($db, $animal_id)
{
  $stmt = $db->prepare('SELECT * from Question where Animal=?');

  $stmt->execute([$animal_id]);

  return $stmt->fetchAll();
}

function getAnimals($db)
{
  $stmt = $db->prepare('SELECT * from Animal');

  $stmt->execute([]);

  return $stmt->fetchAll();
}

function updateListStatus($db, $animal_id, $status)
{
  $stmt = $db->prepare(
    'UPDATE Animal SET listed_for_adoption = ? WHERE id = ?'
  );

  $res = $stmt->execute([$status, $animal_id]);

  if ($status === 1) {
    return $res;
  }

  $stmt = $db->prepare(
    'UPDATE AdoptionProposal SET status = "REFUSED" WHERE animal = ?'
  );

  return $stmt->execute([$animal_id]);
}

function hasAnimal($db, $email, $animal_id)
{
  $stmt = $db->prepare(
    'SELECT animal.id
    FROM Animal, Owns, User
    WHERE Owns.Animal = Animal.id AND Owns.User = User.email AND Animal.id = ? AND user.email = ?'
  );

  $stmt->execute(array($animal_id, $email));
  return $stmt->fetch();
}

function updateOwner($db, $animal, $person)
{
  $stmt = $db->prepare('UPDATE Owns SET user = ? WHERE animal = ?');
   return $stmt->execute([$person, $animal]);
}


function addQuestion($db, $animal_id, $content, $email)
{
  $stmt = $db->prepare('INSERT INTO Question(content, animal, person) values (?, ?, ?)');

  return $stmt->execute([$content, $animal_id,  $email]);
}

function addResponseToQuestion($db, $question_id, $content, $email)
{
  $stmt1 = $db->prepare('INSERT INTO Response(content, person) values (?,?)');

  $error1 = $stmt1->execute([$content, $email]);
  if ($error1 == false) {
    return false;
  }

  $response_id = intval($db->lastInsertId());

  $stmt3 = $db->prepare('INSERT INTO ResponseToQuestion values (?,?)');

  return $stmt3->execute([$question_id, $response_id]);
}

function getQuestionById($db, $question_id)
{
  $stmt = $db->prepare(
    'SELECT *
    FROM Question
    WHERE id = ?
    LIMIT 1'
  );

  $stmt->execute([$question_id]);

  return $stmt->fetch();
}

function getResponseToQuestion($db, $question_id)
{
  $stmt = $db->prepare(
    'SELECT *
    FROM ResponseToQuestion, Response
    WHERE id_question = ? AND id_response = id
    '
  );

  $stmt->execute([$question_id]);

  return $stmt->fetch();
}
