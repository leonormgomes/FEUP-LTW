<?php

/**
 * DEPRECATED, API IN USE
 */

include_once '../templates/common/session_start.php';

if (isset($_SESSION['email'])) {
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  die();
}

if (!isset($_POST["first_name"]) || !isset($_POST["last_name"]) || !isset($_POST["username"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["confirm_password"])) {
  header('Location: ../register.php');
  die();
}

if ($_SESSION['csrf'] !== $_POST['csrf']){
  header('Location: ../register.php');
  die();
}

include_once("../database/connection.php");
include_once("../database/users.php");

$db = getDb();

include_once('../database/validation.php');

// validates the user input
if (
  !is_name($_POST['first_name']) ||
  !is_name($_POST['last_name']) ||
  !is_username($_POST['username']) ||
  !is_email($_POST['email']) ||
  !is_password($_POST['password']) ||
  !is_password($_POST['confirm_password'])
) {
  die(header('Location: ../settings.php'));
}

if ($_POST['password'] !== $_POST['confirm_password']) {
  // TODO notify error
  header("Location: " . $_SERVER['HTTP_REFERER']);
} else if (emailExists($db, $_POST['email'])) {
  // TODO notify error
  header("Location: " . $_SERVER['HTTP_REFERER']);
} else if (usernameExists($db, $_POST['username'])) {
  // TODO notify error
  header("Location: " . $_SERVER['HTTP_REFERER']);
} else if (!insertUser($db, $_POST['email'], $_POST['username'], $_POST['password'], $_POST['first_name'], $_POST['last_name'])) {
  // TODO notify error
  header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
  header("Location: ../login.php");
}
