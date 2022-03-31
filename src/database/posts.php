<?php

function getPostsFromUser($db, $email)
{
  $stmt = $db->prepare(
    'SELECT 
      post.id, post.title, post.content, post.photo, post.animal, post.person, post.created_at, animal.location as animal_location, user.username
    FROM
      User, Post, Animal
    WHERE
      Post.animal = Animal.id AND person = User.email AND User.email = ?
    ORDER BY
      Post.created_at DESC
    '
  );

  $stmt->execute([$email]);

  return $stmt->fetchAll();
}

function getPostsOfAnimal($db, $animal_id)
{
  $stmt = $db->prepare('SELECT * from Post where Animal=?');

  $stmt->execute([$animal_id]);

  return $stmt->fetchAll();
}

function getAllPosts($db)
{
  $stmt = $db->prepare(
    'SELECT
      post.id, post.title, post.content, post.photo, post.animal, post.person, post.created_at, animal.location as animal_location, user.username
    FROM
      User, Post, Animal
    WHERE
      Post.person = User.email AND Post.animal = Animal.id
    ORDER BY
      Post.created_at DESC'
  );

  $stmt->execute();
  return $stmt->fetchAll();
}

function getFollowingPosts($db, $user_email)
{
  $stmt = $db->prepare(
    'SELECT
      post.id, post.title, post.content, post.photo, post.animal, post.person, post.created_at, animal.location as animal_location, user.username
    FROM
      User, Post, Animal, Follows
    WHERE
      Post.person = User.email AND Post.animal = Animal.id AND Follows.user1 = ? AND Post.person = Follows.user2
    ORDER BY
      Post.created_at DESC'
  );

  $stmt->execute([$user_email]);
  return $stmt->fetchAll();
}

function insertPost($db, $title, $animal, $description, $imageFileType, $email)
{
  $stmt = $db->prepare(
    'insert into post(id,title,content,photo,animal,person) values(NULL, ?, ?, ?, ?, ?)'
  );

  if (!$stmt) {
    return -1;
  }

  $stmt->execute([$title, $description, $imageFileType, $animal, $email]);

  $post_id = $db
    ->query('SELECT last_insert_rowid() as last_insert_rowid')
    ->fetch();
  return $post_id['last_insert_rowid'];
}

function getPostbyID($db, $post_id)
{
  $stmt = $db->prepare(
    'SELECT
      post.id, post.title, post.content, post.photo, post.animal, post.person, post.created_at, animal.location as animal_location, user.username
    FROM
      Post, Animal, User
    WHERE
      Post.id = ? AND User.email = Post.person AND Animal.id = Post.animal
    '
  );

  $stmt->execute([$post_id]);
  return $stmt->fetch();
}

function getPostComments($db, $post_id)
{
  $stmt = $db->prepare(
    'SELECT 
      Response.content, Response.person, Response.created_at
    FROM
     Response, ResponseToPost
    WHERE
      ResponseToPost.id_post = ? AND ResponseToPost.id_response= Response.id
    ORDER BY
      Response.created_at DESC'
  );

  $stmt->execute([$post_id]);
  return $stmt->fetchAll();
}

function getTimePassed($date)
{
  $now = time();
  $given_date = strtotime($date);
  $seconds_passed = $now - $given_date;

  if ($seconds_passed < 60) {
    // = 1 minute
    $time_passed = $seconds_passed . 's';
  } elseif ($seconds_passed < 3600) {
    // 60*60 = 1 hour
    $time_passed = floor($seconds_passed / 60) . 'm';
  } elseif ($seconds_passed < 86400) {
    // 60*60*24 = 1 day
    $time_passed = floor($seconds_passed / 3600) . 'h';
  } elseif ($seconds_passed < 604800) {
    // 60*60*24*7 = 1 week
    $time_passed = floor($seconds_passed / 86400) . 'd';
  } elseif ($seconds_passed < 2635200) {
    // 60*60*24*30 = 1 month
    $time_passed = floor($seconds_passed / 604800) . 'w';
  } elseif ($seconds_passed < 31536000) {
    $time_passed = floor($seconds_passed / 2635200) . 'M';
  } else {
    $time_passed = floor($seconds_passed / 31536000) . 'Y';
  } // 60*60*24*365 = 1 years

  return $time_passed;
}

function addComment($db, $post_id, $content, $email)
{
  $stmt1 = $db->prepare('INSERT INTO Response(content, person) values (?, ?)');

  $error1 = $stmt1->execute([$content, $email]);
  if ($error1 == false) {
    return false;
  }

  $comment_id = intval($db->lastInsertId());

  $stmt3 = $db->prepare('INSERT INTO ResponseToPost values (?,?)');

  return $stmt3->execute([$post_id, $comment_id]);
}

function deletePost($db, $post_id)
{
  $stmt = $db->prepare(
    'DELETE FROM Post
    WHERE Post.id = ?'
  );

  $stmt->execute([$post_id]);
  // TODO delete responses also
  $stmt = $db->prepare(
    'DELETE FROM ResponseToPost
     WHERE ResponseToPost.id_post = ?'
  );

  $stmt->execute(array($post_id));
}