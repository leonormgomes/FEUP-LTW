<?php

$input = json_decode(file_get_contents('php://input'), true);

// initializes the session
include_once '../../templates/common/session_start.php';

// gets the database
include_once "../../database/connection.php";
include_once "../../database/posts.php";
$db = getDb();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    // gets all the comments for a given post

    if ($input === null)
      $input = $_GET;

    // verifies if the post id was passed
    if (!isset($input['postID'])) {
      echo json_encode(['error' => 'Missing postID value']);
      die(http_response_code(400));
    }

    // verifies if the post exists
    $post = getPostbyID($db, $input['postID']);
    if (!$post) {
      echo json_encode(['error' => 'The postID is invalid']);
      die(http_response_code(400));
    }

    // gets the post comments
    echo json_encode(getPostComments($db, $input['postID']));
    die(http_response_code(200));
    break;

  case 'POST':
    // adds a new comment to a given post

    if ($input === null)
       $_POST;

    // verifies if logged in
    if (!isset($_SESSION['email'])) {
      echo json_encode(['error' => 'User not logged in']);
      die(http_response_code(401));
    }

    // verifies if has all the fields
    $fields = [
      'postID',
      'content',
      'csrf'
    ];

    // verifies if all the requires parameters were passed
    include_once '../util/validation.php';

    $missing_field = has_fields($input, $fields);
    if (!$missing_field[0]) {
      echo json_encode(['error' => 'Missing ' . $missing_field[1] . ' value', 'required' => $fields]);
      die(http_response_code(400));
    }

    // verifies if the csrf token is valid
    if ($_SESSION['csrf'] !== $input['csrf']) {
      echo json_encode(['error' => 'Invalid CSRF Token']);
      die(http_response_code(400));
    }

    // verifies if the post exists
    $post = getPostbyID($db, $input['postID']);
    if (!$post) {
      echo json_encode(['error' => 'The postID is invalid']);
      die(http_response_code(400));
    }

    // adds the comment
    addComment($db, $input['postID'], $input['content'], $_SESSION['email']);

    echo json_encode(['message' => 'Commented']);
    die(http_response_code(200));
    break;

  default:
    echo json_encode(['error' => $_SERVER['REQUEST_METHOD'] . ' not allowed', 'allowed' => ['GET', 'POST']]);
    die(http_response_code(403));
}
