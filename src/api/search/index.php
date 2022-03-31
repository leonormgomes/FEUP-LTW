<?php

$decoded = json_decode(file_get_contents('php://input'), true);

// initializes the session
include_once '../../templates/common/session_start.php';

// gets the database
include_once '../../database/connection.php';
include_once '../util/search.php';
$db = getDb();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $input = $decoded === null ? $_GET : $decoded;

    // gets all the comments for a given post

    // verifies if the post id was passed
    if (!isset($input['search'])) {
      echo json_encode(['error' => 'Missing search value']);
      die(http_response_code(400));
    }

    $animal_array = search_parameter(
      $db,
      'animal',
      ['name', 'age', 'location', 'species'],
      $input['search']
    );

    $user_array = search_parameter(
      $db,
      'user',
      ['username', 'location', 'first_name', 'last_name'],
      $input['search']
    );

    echo json_encode(['animals' => $animal_array, 'users' => $user_array]);
    die(http_response_code(200));

  default:
    echo json_encode([
      'error' => $_SERVER['REQUEST_METHOD'] . ' not allowed',
      'allowed' => ['GET', 'POST'],
    ]);
    die(http_response_code(403));
}