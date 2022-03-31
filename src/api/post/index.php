<?php

$input = json_decode(file_get_contents('php://input'), true);

// initializes the session
include_once '../../templates/common/session_start.php';

// gets the database
include_once "../../database/connection.php";
include_once "../../database/posts.php";
include_once '../../database/users.php';
include_once '../../database/animals.php';
$db = getDb();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    // gets all the posts or the posts of a given user

    if ($input === null)
      $input = $_GET;

    if (isset($input['username'])) {
      // if the user was given, gets the posts of the given user

      $user = getUserByUsername($db, $input['username']);
      if (!$user) {
        echo json_encode(['error' => 'The username is invalid']);
        die(http_response_code(400));
      }

      echo json_encode(getPostsFromUser($db, $user['email']));
      die(http_response_code(200));
    }

    // if the uer was not given, gets all the posts
    echo json_encode(getAllPosts($db));
    die(http_response_code(200));
    break;

  case 'POST':
    if ($input === null)
      $input = $_POST;

    include_once '../../database/images.php';

    if (!isset($_SESSION["email"])) {
      echo json_encode(['error' => 'User not logged in']);
      die(http_response_code(401));
    }

    if (!(file_exists($_FILES["photo"]["tmp_name"]) && is_uploaded_file($_FILES["photo"]["tmp_name"]))) {
      echo json_encode(['error' => 'Invalid photo']);
      die(http_response_code(400));
    }
    $target_file = updateImage($_FILES["photo"]["name"]);

    if (!isset($input['title'])) {
      echo json_encode(['error' => 'Missing title value']);
      die(http_response_code(400));
    }

    if (!isset($input['animal'])) {
      echo json_encode(['error' => 'Missing animal value']);
      die(http_response_code(400));
    }

    $animal = hasAnimal($db, $_SESSION['email'], $input['animal']);
    if (!$animal) {
      echo json_encode(['error' => 'Animal does not belong to the user']);
      die(http_response_code(400));
    }

    if (!isset($input['description'])) {
      echo json_encode(['error' => 'Missing description value']);
      die(http_response_code(400));
    }

    $post_id = insertPost($db, $input['title'], $input['animal'], $input['description'], $target_file, $_SESSION['email']);

    move_uploaded_file(
      $_FILES['photo']['tmp_name'],
      getImageURL($target_file)
    );

    echo json_encode(['message' => 'Post added']);
    die(http_response_code(200));
    break;

  case 'DELETE':
    // deletes a given post, must own it

    // verifies if logged in
    if (!isset($_SESSION['email'])) {
      echo json_encode(['error' => 'User not logged in']);
      die(http_response_code(401));
    }

    // verifies if has all the fields
    $fields = [
      'postID',
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

    // verifies if the user owns the post
    if (!in_array($post, getPostsFromUser($db, $_SESSION['email']))) {
      echo json_encode(['error' => 'The post does not belong to the logged user']);
      die(http_response_code(401));
    }

    // deletes the post
    deletePost($db, $input["postID"]);

    echo json_encode(['message' => 'Deleted']);
    die(http_response_code(200));
    break;

  default:
    echo json_encode(['error' => $_SERVER['REQUEST_METHOD'] . ' not allowed', 'allowed' => ['DELETE', 'GET', 'POST']]);
    die(http_response_code(403));
    break;
}
