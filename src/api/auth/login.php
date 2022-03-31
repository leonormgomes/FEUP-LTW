<?php

$input = json_decode(file_get_contents('php://input'), true);

// initializes the session
include_once '../../templates/common/session_start.php';

include_once '../../database/connection.php';
include_once '../../database/users.php';
$db = getDB();

// verifies the request method
switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    if ($input === null) {
      $input = $_GET;
    }

    // verifies if logged in
    if (!isset($_SESSION['email'])) {
      echo json_encode(['error' => 'User not logged in']);
      die(http_response_code(401));
    }

    // gets the user information
    echo json_encode(getUserInfo($db, $_SESSION['email']));
    die(http_response_code(200));
    break;

  case 'POST':
    if ($input === null) {
      $input = $_POST;
    }

    // verifies if the user is already logged in
    if (isset($_SESSION['email'])) {
      echo json_encode([
        'error' => 'Already logged in',
        'email' => $_SESSION['email'],
      ]);
      die(http_response_code(403));
    }

    // verifies if all the requires parameters were passed
    include_once '../util/validation.php';

    $fields = ['email', 'password', 'csrf'];

    $missing_field = has_fields($input, $fields);
    if (!$missing_field[0]) {
      echo json_encode([
        'error' => 'Missing ' . $missing_field[1] . ' value',
        'required' => ['email', 'password', 'csrf'],
      ]);
      die(http_response_code(400));
    }

    // verifies if the post request came from the valid source
    if ($_SESSION['csrf'] !== $input['csrf']) {
      echo json_encode([
        'error' => 'Invalid CSRFToken',
        'should' => $_SESSION['csrf'],
      ]); // testing
      die(http_response_code(403));
    }

    // tries to login
    if (userExists($db, $input['email'], $input['password'])) {
      $_SESSION['email'] = $input['email'];

      echo json_encode(['message' => 'Logged in']);
      die(http_response_code(200));
    } else {
      // in case of failure, verifies if the email exists at least
      if (emailExists($db, $input['email'])) {
        echo json_encode(['error' => 'Incorrect password']);
      } else {
        echo json_encode(['error' => 'Invalid email']);
      }

      die(http_response_code(401));
    }

    break;

  default:
    echo json_encode([
      'error' => $_SERVER['REQUEST_METHOD'] . ' not allowed',
      'allowed' => ['GET', 'POST'],
    ]);
    die(http_response_code(403));
    break;
}