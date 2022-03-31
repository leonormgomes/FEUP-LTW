<?php

$input = json_decode(file_get_contents('php://input'), true);

include_once '../../templates/common/session_start.php';
include_once '../util/validation.php';
include_once '../../database/users.php';
include_once '../../database/animals.php';

include_once '../../database/connection.php';
$db = getDB();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'POST':
    if ($input === null) {
      $input = $_POST;
    }

    $required_fields = [
      'csrf',
      'name',
      'age',
      'size',
      'species',
      'listed',
      'location',
      'description',
    ];

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

    $not_empty = [
      'name',
      'age',
      'size',
      'species',
      'listed',
      'location',
      'description',
    ];

    if (!not_empty_fields($input, $not_empty)) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Empty fields']);
      die(http_response_code(400));
    }

    if ($input['listed'] != 1 && $input['listed'] != 0) {
      header('Content-Type: application/json');
      echo json_encode([
        'error' => 'Listed for adoption must be yes (1) or no (0)',
      ]);
      die(http_response_code(400));
    }

    include_once '../../database/connection.php';
    include_once '../../database/animal.php';
    include_once '../../database/images.php';

    if (
      !(
        file_exists($_FILES['photo']['tmp_name']) &&
        is_uploaded_file($_FILES['photo']['tmp_name'])
      )
    ) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Invalid photo']);
      die(http_response_code(400));
    }

    $animal_photo = updateImage($_FILES['photo']['name']);
    // $image = imagecreatefromjpeg($animal_photo);
    // imagejpeg($image, $animal_photo);
    $db = getDB();

    if (
      !insertAnimal(
        $db,
        $input['name'],
        $animal_photo,
        $input['description'],
        $input['location'],
        $input['age'],
        $input['size'],
        $input['description'],
        $input['listed']
      )
    ) {
      echo json_encode(['error' => 'Could not save animal in database']);
      die(http_response_code(400));
    }

    insertAnimalOwner($db, $_SESSION['email'], lastInsertedAnimalId($db));

    move_uploaded_file(
      $_FILES['photo']['tmp_name'],
      getImageURL($animal_photo)
    );

    echo json_encode(['message' => 'Animal added']);
    die(http_response_code(200));

  case 'GET':
    if ($input === null) {
      $input = $_GET;
    }

    if (!isset($input['username'])) {
      echo json_encode(getAnimals($db));
      die(http_response_code(200));
    }

    // verifies if the user exists
    $user = getUserByUsername($db, $input['username']);
    if (!$user) {
      echo json_encode(['error' => 'User does not exist']);
      die(http_response_code(400));
    }

    echo json_encode(animalsofUser($db, $user['email']));
    die(http_response_code(200));

  default:
    echo json_encode([
      'error' => $_SERVER['REQUEST_METHOD'] . ' not allowed',
      'allowed' => ['GET', 'POST'],
    ]);
    die(http_response_code(403));
    break;
}