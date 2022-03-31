<?php
session_start();
include_once("../database/connection.php");
include_once("../database/users.php");
include_once("../database/posts.php");

$db = getDb();

if (!isset($_SESSION["email"])) {
  header('Location: ../login.php');
  die();
}

$target_dir = "../database/images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if ($_FILES["fileToUpload"]["size"] > 500000) {
  //TO DO give some kind of message
  $uploadOk = 0;
}

$target_file = microtime(true) . "." . $imageFileType;

$post_id = insertPost($db, $_POST['title'], $_POST['animal'], $_POST['description'], $target_file, $_SESSION['email']);

$target_file = $target_dir . microtime(true) . "." . $imageFileType;

if ($post_id == -1 || $post_id == 0) {
  header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
  if ($uploadOk == 0) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
    } else {
      header("Location: " . $_SERVER['HTTP_REFERER']);
    }
  }
  $username = getUsernameByEmail($db, $_SESSION['email']);
  header("Location: ../profile.php?username=" . $username[0]['email']);
}
