<?php

$input = json_decode(file_get_contents('php://input'), true);

include_once '../../database/connection.php';
include_once '../../database/follows.php';
include_once '../../database/users.php';
include_once '../../templates/common/session_start.php';

$db = getDB();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    // in the GET request, gets the usernames of the users that the given user follows

    if ($input === null)
      $input = $_GET;

    // verifies if the username was sent
    if (!isset($input['username'])) {
      echo json_encode(['error' => 'Missing username value']);
      die(http_response_code(400));
    }

    // verifies if the user exists
    $user = getUserByUsername($db, $input['username']);
    if (!$user) {
      echo json_encode(['error' => 'The user ' . $input['username'] . ' does not exist']);
      die(http_response_code(400));
    }

    echo json_encode(getFollowing($db, $user['email']));
    die(http_response_code(200));
    break;

  case 'POST':
    // in the POST request, the logged in user follows the given user

    if ($input === null)
      $input = $_POST;

    // verifies if the username was sent
    if (!isset($input['username'])) {
      echo json_encode(['error' => 'Missing username value']);
      die(http_response_code(400));
    }

    // verifies if the user exists
    $user = getUserByUsername($db, $input['username']);
    if (!$user) {
      echo json_encode(['error' => 'The user ' . $input['username'] . ' does not exist']);
      die(http_response_code(400));
    }

    // verifies if logged in
    if (!isset($_SESSION['email'])) {
      echo json_encode(['error' => 'User not logged in']);
      die(http_response_code(401));
    }

    if ($_SESSION['email'] === $user['email']) {
      echo json_encode(['error' => 'Can\'t follow itself']);
      die(http_response_code(400));
    }

    // verifies if the csrf was sent
    if (!isset($input['csrf'])) {
      echo json_encode(['error' => 'Missing CSRF token']);
      die(http_response_code(400));
    }

    // verifies if the csrf token is valid
    if ($_SESSION['csrf'] !== $input['csrf']) {
      echo json_encode(['error' => 'Invalid CSRF Token']);
      die(http_response_code(400));
    }

    // verifies if already follows
    if (checksFollows($db, $_SESSION['email'], $user['email'])) {
      echo json_encode(['message' => 'Already following']);
      die(http_response_code(200));
    } else {
      insertFollow($db, $_SESSION['email'], $user['email']);
      echo json_encode(['message' => 'Started following']);
      die(http_response_code(200));
    }
    break;

  default:
    echo json_encode(['error' => $_SERVER['REQUEST_METHOD'] . ' not allowed', 'allowed' => ['GET', 'POST']]);
    die(http_response_code(403));
}
