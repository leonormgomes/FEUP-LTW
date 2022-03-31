<?php
include_once '../templates/common/session_start.php';

if (!isset($_SESSION['email'])) {
  header('Location: ../index.php');
  die();
}

if ($_SESSION['csrf'] !== $_POST['csrf']){
  header('Location: ../index.php');
  die();
}

include_once("../database/connection.php");
include_once("../database/posts.php");

$db = getDb();

deletePost($db, $_POST["post_id"]);

die(header('Location: ../profile.php'));