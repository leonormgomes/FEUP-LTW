<?php

/**
 * DEPRECATED, API IN USE
 */

include_once '../templates/common/session_start.php';

if (isset($_SESSION['email'])) {
  header('Location: ../feed.php');
  die();
}

if ($_SESSION['csrf'] !== $_POST['csrf']) {
  header('Location: ../feed.php');
  die();
}

if (!isset($_POST["email"]) || !isset($_POST["password"])) {
  header('Location: ../login.php');
  die();
}


include_once("../database/connection.php");
include_once("../database/users.php");

$db = getDB();

if (userExists($db, $_POST["email"], $_POST["password"])) {
  $_SESSION["email"] = $_POST["email"];
  header("Location: ../feed.php");
} else {
  header('Location: ../login.php');
}
