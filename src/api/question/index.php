<?php

$input = json_decode(file_get_contents('php://input'), true);

include_once '../../templates/common/session_start.php';

include_once "../../database/connection.php";
include_once "../../database/animals.php";

$db = getDB();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    if ($input === null)
      $input = $_GET;

    if (!isset($input['animalID'])) {
      echo json_encode(['error' => 'Missing animalID value']);
      die(http_response_code(400));
    }

    if (!getAnimalById($db, $input['animalID'])) {
      echo json_encode(['error' => 'Animal does not exist']);
      die(http_response_code(400));
    }

    echo json_encode(getQuestionsOfAnimal($db, $input['animalID']));
    die(http_response_code(200));
    break;

  case 'POST':
    if ($input === null)
      $input = $_POST;

    // verifies if logged in
    if (!isset($_SESSION['email'])) {
      echo json_encode(['error' => 'User not logged in']);
      die(http_response_code(401));
    }

    if (!isset($input['animalID'])) {
      echo json_encode(['error' => 'Missing animalID value']);
      die(http_response_code(400));
    }

    if (!isset($input['content'])) {
      echo json_encode(['error' => 'Missing content value']);
      die(http_response_code(400));
    }

    addQuestion($db, $input['animalID'], $input['content'], $_SESSION['email']);
    echo json_encode(['message' => 'Question made']);
    die(http_response_code(200));
    break;

  default:
    echo json_encode(['error' => $_SERVER['REQUEST_METHOD'] . ' not allowed', 'allowed' => ['GET', 'POST']]);
    die(http_response_code(403));
    break;
}
