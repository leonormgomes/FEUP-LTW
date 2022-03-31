<?php
function getFavorites($db, $email)
{
  $stmt = $db->prepare(
    'SELECT favorite.animal, id, name, photo, description, location, age, size, species, listed_for_adoption
        FROM Favorite, Animal
        WHERE user = ? AND Animal.id = Favorite.animal
        ORDER BY id'
  );

  if (!$stmt)
    return False;

  $stmt->execute(array($email));
  return $stmt->fetchAll();
}

function getFavoritesOfUsername($db, $username)
{
  $stmt = $db->prepare(
    'SELECT favorite.animal, animal.id, animal.name, animal.photo, animal.description, animal.location, animal.age, animal.size, animal.species, animal.listed_for_adoption
        FROM Favorite, Animal, User
        WHERE user = user.email AND user.username = ? AND Animal.id = Favorite.animal
        ORDER BY id'
  );

  if (!$stmt)
    return False;

  $stmt->execute(array($username));
  return $stmt->fetchAll();
}

function insertFavorite($db, $email, $animal_id)
{
  $stmt = $db->prepare(
    'INSERT INTO Favorite values(?, ?)'
  );

  return $stmt->execute(array($email, $animal_id));
}

function unfavorite($db, $email, $animal_id)
{
  $stmt = $db->prepare(
    'DELETE FROM Favorite
       WHERE user=? and animal=?'
  );

  $stmt->execute(array($email, $animal_id));

  return $stmt->fetch();
}

function isFavorite($db, $email, $animal_id)
{
  $stmt = $db->prepare(
    'SELECT *
    FROM Favorite
    WHERE user=? and animal=?'
  );

  $stmt->execute(array($email, $animal_id));

  return $stmt->fetch();
}
