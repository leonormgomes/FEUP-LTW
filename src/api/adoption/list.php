<?php

$input = json_decode(file_get_contents('php://input'), true);

include_once '../../templates/common/session_start.php';

include_once '../util/validation.php';
include_once '../../database/users.php';
include_once '../../database/animals.php';
include_once '../../database/adoption.php';
include_once '../../database/connection.php';

$db = getDB();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    if ($input === null) {
      $input = $_POST;
    }

    $required_fields = ['csrf', 'animal_id'];

    list($has_fields, $field) = has_fields($input, $required_fields);

    if (!$has_fields) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Missing field ' . $field]);
      die(http_response_code(400));
    }

    if ($_SESSION['csrf'] !== $input['csrf']) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Invalid session']);
      die(http_response_code(400));
    }

    // verifies if the animal exists
    $animal = getAnimalById($db, $input['animal_id']);
    if (!$animal) {
      echo json_encode(['error' => 'Animal does not exist']);
      die(http_response_code(400));
    }

    $owner = getAnimalOwner($db, $input['animal_id']);

    if ($owner['email'] !== $_SESSION['email']) {
      echo json_encode(['error' => 'You must be the owner']);
      die(http_response_code(400));
    }

    echo json_encode(['message' => 'Listed']);
    updateListStatus($db, $input['animal_id'], 1);

    die(http_response_code(200));
    break;

  case 'DELETE':
    if ($input === null) {
      $input = $_POST;
    }

    $required_fields = ['csrf', 'animal_id'];

    list($has_fields, $field) = has_fields($input, $required_fields);

    if (!$has_fields) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Missing field ' . $field]);
      die(http_response_code(400));
    }

    if ($_SESSION['csrf'] !== $input['csrf']) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Invalid session']);
      die(http_response_code(400));
    }

    // verifies if the animal exists
    $animal = getAnimalById($db, $input['animal_id']);
    if (!$animal) {
      echo json_encode(['error' => 'Animal does not exist']);
      die(http_response_code(400));
    }

    $owner = getAnimalOwner($db, $input['animal_id']);

    if ($owner['email'] !== $_SESSION['email']) {
      echo json_encode(['error' => 'You must be the owner']);
      die(http_response_code(400));
    }

    updateListStatus($db, $input['animal_id'], 0);

    echo json_encode(['message' => 'Deleted']);
    die(http_response_code(200));
    break;

  default:
    echo json_encode([
      'error' => $_SERVER['REQUEST_METHOD'] . ' not allowed',
      'allowed' => ['GET', 'DELETE'],
    ]);
    die(http_response_code(403));
    break;
}
