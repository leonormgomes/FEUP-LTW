<?php

function getFollowingUsernames($db, $email)
{
  $stmt = $db->prepare(
    'SELECT username
    FROM
      Follows, User
    WHERE
      Follows.user1 = ? and User.email = Follows.user2
    '
  );

  $stmt->execute([$email]);

  return $stmt->fetchAll();
}

function getFollowing($db, $email)
{
  $stmt = $db->prepare(
    'SELECT email, username, first_name, last_name, phone_number, description, profile_picture, cover_picture, location, birth_date
    FROM
      Follows, User
    WHERE
      Follows.user1 = ? and User.email = Follows.user2
    '
  );

  $stmt->execute([$email]);

  return $stmt->fetchAll();
}

function getFollowers($db, $email)
{
  $stmt = $db->prepare(
    'SELECT username, profile_picture
    FROM
      Follows, User
    WHERE
      Follows.user2 = ? and User.email = Follows.user1
    '
  );

  $stmt->execute([$email]);

  return $stmt->fetchAll();
}

function insertFollow($db, $email1, $email2)
{
  $stmt = $db->prepare(
    'INSERT INTO Follows values(?, ?)'
  );

  return $stmt->execute(array($email1, $email2));

}

function checksFollows($db, $email1, $email2)
{
  $stmt = $db->prepare(
    'SELECT * 
    FROM Follows
    WHERE user1=? and user2=?'
  );

  $stmt->execute(array($email1, $email2));

  return $stmt->fetch();
}

function unfollow($db, $email1, $email2){
  $stmt = $db->prepare(
    'DELETE FROM Follows
     WHERE user1=? and user2=?');

  $stmt->execute(array($email1, $email2));

  return $stmt->fetch();
}