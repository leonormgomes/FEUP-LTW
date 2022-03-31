<?php

/**
 * DEPRECATED, API IN USE
 */

include_once '../templates/common/session_start.php';

if (!isset($_SESSION['email'])) {
  header('Location: ' . $_SERVER['HTTP_REFERER']); // TODO header to feed
  die();
}

if ($_SESSION['csrf'] !== $_POST['csrf']){
  die();
  header('Location: ../feed.php');
  die();
}

include_once("../database/connection.php");
$db = getDb();

include_once("../database/posts.php");

addComment($db, $_POST['postID'], $_POST['content'], $_SESSION['email']);

die(header('Location: ' . $_SERVER['HTTP_REFERER']));