<?php

/**
 * DEPRECATED, API IN USE
 */

include_once '../templates/common/session_start.php';

include_once '../database/users.php';
include_once '../database/connection.php';
include_once '../database/follows.php';

$db = getDB();

if (!isset($_SESSION['email']) || !isset($_POST['user']))
  die(header('Location: ../index.php'));

if (!emailExists($db, $_SESSION['email']) || !emailExists($db, $_POST['user']))
  die(header('Location: ../index.php'));

if ($_SESSION['csrf'] !== $_POST['csrf']){
  header('Location: ../index.php');
  die();
}

if(checksFollows($db, $_SESSION['email'], $_POST['user']))
  unfollow($db, $_SESSION['email'], $_POST['user']);

header('Location: '. $_SERVER['HTTP_REFERER']);