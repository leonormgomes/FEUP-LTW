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

    $required_fields = ['csrf', 'animal_id', 'person_email'];

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

    if (!emailExists($db, $input['person_email'])) {
      echo json_encode(['error' => 'Person does not exist']);
      die(http_response_code(400));
    }

    // verifies if the animal exists
    $animal = getAnimalById($db, $input['animal_id']);
    if (!$animal) {
      echo json_encode(['error' => 'Animal does not exist']);
      die(http_response_code(400));
    }

    $owner = getAnimalOwner($db, $input['animal_id']);

    if (
      $_SESSION['email'] !== $input['person_email'] &&
      $_SESSION['email'] !== $owner['email']
    ) {
      echo json_encode([
        'error' => 'You must be the owner or have a pending proposal',
      ]);
      die(http_response_code(400));
    }

    updateAdoptionProposal(
      $db,
      $input['animal_id'],
      $input['person_email'],
      'REFUSED'
    );

    echo json_encode(['message' => 'Refused']);
    die(http_response_code(200));
    break;

  default:
    echo json_encode([
      'error' => $_SERVER['REQUEST_METHOD'] . ' not allowed',
      'allowed' => ['POST'],
    ]);
    die(http_response_code(403));
    break;
}