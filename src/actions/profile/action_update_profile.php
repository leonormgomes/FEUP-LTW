<?php
include_once '../../templates/common/session_start.php';

if (!isset($_SESSION['email']))
  die(header('Location: ../index.php'));

if (!isset($_POST["submit"]))
  die(header('Location: ../settings.php'));

// verifies if the post came from the same session
if ($_SESSION['csrf'] !== $_POST['csrf'])
  die(header('Location: ../index.php'));

include_once "../../database/connection.php";
include_once "../../database/users.php";
include_once "../../database/images.php";

// gets the user information
$db = getDb();
$user = getUserInfo($db, $_SESSION['email']);

// verifies if the username already exists
if ($_POST['username'] != $user['username'] && usernameExists($db, $_POST['username']))
  die(header('Location: ../settings.php')); // TODO notify error

$profile_picture_name = $user['profile_picture'];
$cover_picture_name = $user['cover_picture'];

// updates the html images
if (file_exists($_FILES['profile_picture']['tmp_name']) && is_uploaded_file($_FILES['profile_picture']['tmp_name']))
  $profile_picture_name = updateImage($_FILES['profile_picture']['name']);
if (file_exists($_FILES['cover_picture']['tmp_name']) && is_uploaded_file($_FILES['cover_picture']['tmp_name']))
  $cover_picture_name = updateImage($_FILES['cover_picture']['name']);

include_once '../../database/validation.php';

// validates the user input
if (
  !is_name($_POST['first_name']) ||
  !is_name($_POST['last_name']) ||
  $_POST['description'] && !is_profile_description($_POST['description']) ||
  !is_username($_POST['username']) ||
  $_POST['location'] && !is_location($_POST['location']) ||
  $_POST['phone_number'] && !is_phone_number($_POST['phone_number']) ||
  $_POST['birth_date'] && !is_date($_POST['birth_date'])
) {
  die(header('Location: ../settings.php'));
}

// updates the user information
if (updateUser(
  $db,
  $_SESSION['email'],
  $_POST['username'],
  $_POST['first_name'],
  $_POST['last_name'],
  $_POST['phone_number'],
  $_POST['description'],
  $profile_picture_name,
  $cover_picture_name,
  $_POST['location'],
  $_POST['birth_date']
)) {
  // saves the images
  if (file_exists($_FILES['profile_picture']['tmp_name']) && is_uploaded_file($_FILES['profile_picture']['tmp_name']))
    move_uploaded_file($_FILES['profile_picture']['tmp_name'], getImageURL($profile_picture_name));
  if (file_exists($_FILES['cover_picture']['tmp_name']) && is_uploaded_file($_FILES['cover_picture']['tmp_name']))
    move_uploaded_file($_FILES['cover_picture']['tmp_name'], getImageURL($cover_picture_name));

  // TODO notify success
  header("Location: ../../profile.php?username=" . $_POST['username']);
} else {
  // TODO notify error
  header("Location: " . $_SERVER['HTTP_REFERER']);
}
