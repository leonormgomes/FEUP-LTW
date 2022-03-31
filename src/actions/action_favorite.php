<?php 

include_once '../database/users.php';
include_once '../database/connection.php';
include_once '../database/follows.php';
include_once '../database/favorites.php';
session_start();
$db = getDb();

 if (!isset($_SESSION["email"])) {
  header('Location: ../index.php');
  die();
  } 
  if (!isset($_POST['animal_id'])) {
    header('Location: ../index.php');
    die();
  } 



insertFavorite($db,$_SESSION['email'],$_POST['animal_id']);
header('Location: '. $_SERVER['HTTP_REFERER']);
