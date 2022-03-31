<?php

function userExists($db, $given_email, $password)
{
  $email = strtolower($given_email);

  $stmt = $db->prepare(
    'SELECT email, password
    FROM User
    WHERE email = ?
    LIMIT 1'
  );
  if (!$stmt) {
    return false;
  }

  $stmt->execute([$email]);

  $user = $stmt->fetch();
  if (!$user) {
    return false;
  }

  return password_verify($password, $user["password"]);
}

function emailExists($db, $given_email)
{
  $email = strtolower($given_email);

  $stmt = $db->prepare(
    'SELECT *
        FROM User
        WHERE email = ?
        LIMIT 1'
  );
  if (!$stmt) {
    return false;
  }

  $stmt->execute([$email]);

  return ($stmt->fetch()) ? True : False;
}

function usernameExists($db, $given_username)
{
  $username = strtolower($given_username);

  $stmt = $db->prepare(
    'SELECT *
        FROM User
        WHERE username = ?
        LIMIT 1'
  );
  if (!$stmt) {
    return false;
  }

  $stmt->execute([$username]);

  return ($stmt->fetch()) ? True : False;
}

function insertUser($db, $given_email, $given_username, $password, $first_name, $last_name)
{
  $email = strtolower($given_email);
  $username = strtolower($given_username);
  $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

  $stmt = $db->prepare(
    'INSERT INTO User(email, username, password, first_name, last_name)
        VALUES(?, ?, ?, ?, ?)'
  );
  if (!$stmt) {
    return false;
  }

  $stmt->execute([$email, $username, $password, $first_name, $last_name]);
  return true;
}

function getUserByUsername($db, $given_username)
{
  $username = strtolower($given_username);

  $stmt = $db->prepare(
    'SELECT username, first_name, last_name, phone_number, description, profile_picture, cover_picture, location, birth_date, email
    FROM User
    WHERE username = ?
    LIMIT 1'
  );

  $stmt->execute([$username]);

  return $stmt->fetch();
}

function getUsernameByEmail($db, $email)
{
  $stmt = $db->prepare(
    'SELECT username, first_name, last_name, phone_number, description, profile_picture, cover_picture, location, birth_date, email
    FROM User
    WHERE email = ?
    LIMIT 1'
  );

  $stmt->execute([$email]);

  return $stmt->fetch();
}


function updateUser(
  $db,
  $email,
  $username,
  $first_name,
  $last_name,
  $phone_number,
  $description,
  $profile_picture,
  $cover_picture,
  $location,
  $birth_date
) {
  $stmt = $db->prepare(
    'UPDATE User
    SET username = ?,
        first_name = ?,
        last_name = ?,
        phone_number = ?,
        description = ?,
        profile_picture = ?,
        cover_picture = ?,
        location = ?,
        birth_date = ?
    WHERE email = ?'
  );
  if (!$stmt)
    return False;

  return $stmt->execute(array(
    $username, $first_name, $last_name, $phone_number, $description, $profile_picture,
    $cover_picture, $location, $birth_date, $email
  ));
}

function getUserInfo($db, $given_email)
{
  $email = strtolower($given_email);

  $stmt = $db->prepare(
    'SELECT email, username, first_name, last_name, phone_number, description, profile_picture, cover_picture, location, birth_date
    FROM User
    WHERE email = ?
    LIMIT 1'
  );

  $stmt->execute(array($email));

  return $stmt->fetch();
}

function animalsofUser($db, $email)
{
  $stmt = $db->prepare(
    'SELECT Animal.name, Animal.id, Animal.photo, Animal.size, Animal.location, Animal.age, Animal.species
        FROM User
        INNER JOIN Owns on Owns.User=User.email
        INNER JOIN Animal on Animal.id=Owns.Animal
        WHERE User.email=?
        ORDER BY Animal.name ASC'
  );

  if (!$stmt)
    return False;

  $stmt->execute(array($email));
  return $stmt->fetchAll();
}

function animalsofUsername($db, $username)
{
  $stmt = $db->prepare(
    'SELECT Animal.name, Animal.id, Animal.photo, Animal.size, Animal.location, Animal.age, Animal.species
        FROM User
        INNER JOIN Owns on Owns.User=User.email
        INNER JOIN Animal on Animal.id=Owns.Animal
        WHERE User.username=?
        ORDER BY Animal.name ASC'
  );

  if (!$stmt)
    return False;

  $stmt->execute(array($username));
  return $stmt->fetchAll();
}

function getUsers($db)
{
  $stmt = $db->prepare('SELECT email, username, first_name, last_name, phone_number, description, profile_picture, cover_picture, location, birth_date
  FROM User');

  $stmt->execute();

  return $stmt->fetch();
}
