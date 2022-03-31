<?php
include_once 'templates/common/session_start.php';

if (!isset($_SESSION['email'])) {
  header('Location: login.php');
  die();
}

include_once("database/connection.php");
include_once("database/users.php");
include_once("database/images.php");

$db = getDb();
$user = getUserInfo($db, $_SESSION['email']);

include 'templates/common/head.php';
include 'templates/common/navbar.php';
include 'templates/settings/settings.php';
include 'templates/common/footer.php';
