<?php

$input = json_decode(file_get_contents('php://input'), true);

include_once '../../database/users.php';
include_once '../../database/favorites.php';
include_once '../../database/animals.php';
include_once '../../database/connection.php';

$db = getDB();

switch ($_SERVER['REQUEST_METHOD']) {
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

    if (!isFavorite($db, $_SESSION['email'], $input['animalID'])) {
      echo json_encode(['message' => 'Already not a favorite']);
      die(http_response_code(200));
    }

    unfavorite($db, $_SESSION['email'], $input['animalID']);
    echo json_encode(['message' => 'Unfavorited']);
    die(http_response_code(200));

  default:
    echo json_encode(['error' => $_SERVER['REQUEST_METHOD'] . ' not allowed', 'allowed' => ['POST']]);
    die(http_response_code(403));
    break;
}
