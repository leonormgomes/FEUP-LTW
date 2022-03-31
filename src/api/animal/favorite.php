<?php

$input = json_decode(file_get_contents('php://input'), true);

include_once '../../database/users.php';
include_once '../../database/favorites.php';
include_once '../../database/animals.php';
include_once '../../database/connection.php';

$db = getDB();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    if ($input === null)
      $input = $_GET;

    if (!isset($input['username'])) {
      echo json_encode(['error' => 'Missing username value']);
      die(http_response_code(400));
    }

    // verifies if the user exists
    $user = getUserByUsername($db, $input['username']);
    if (!$user) {
      echo json_encode(['error' => 'User does not exist']);
      die(http_response_code(400));
    }

    echo json_encode(getFavorites($db, $user['email']));
    die(http_response_code(200));

  case 'POST':
    if ($input === null)
      $input = $_POST;

    include_once '../../templates/common/session_start.php';

    if (!isset($_SESSION["email"])) {
      echo json_encode(['error' => 'User not logged in']);
      die(http_response_code(401));
    }

    if (!isset($input['animalID'])) {
      echo json_encode(['error' => 'Missing animalID']);
      die(http_response_code(401));
    }

    // verifies if the animal exists
    $animal = getAnimalById($db, $input['animalID']);
    if (!$animal) {
      echo json_encode(['error' => 'Animal does not exist']);
      die(http_response_code(400));
    }

    if (isFavorite($db, $_SESSION['email'], $input['animalID'])) {
      echo json_encode(['message' => 'Already favorite']);
      die(http_response_code(200));
    }

    // TODO dont let add own

    insertFavorite($db, $_SESSION['email'], $input['animalID']);
    echo json_encode(['message' => 'Favorited']);
    die(http_response_code(200));

  default:
    echo json_encode(['error' => $_SERVER['REQUEST_METHOD'] . ' not allowed', 'allowed' => ['GET', 'POST']]);
    die(http_response_code(403));
    break;
}
