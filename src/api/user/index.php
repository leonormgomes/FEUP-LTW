<?php
// gets the post parameters
$input = json_decode(file_get_contents('php://input'), true);

// initializes the session
include_once '../../templates/common/session_start.php';

include_once "../../database/users.php";

include_once "../../database/connection.php";

$db = getDb();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    // gets the information about a user given its username

    if ($input === null)
      $input = $_GET;

    if (!isset($input['username'])) {
      echo json_encode(getUsers($db));
      die(http_response_code(200));
    }

    // verifies if the user exists
    $user = getUserByUsername($db, $input['username']);
    if (!$user) {
      echo json_encode(['error' => 'User does not exist']);
      die(http_response_code(400));
    }

    echo json_encode($user);
    die(http_response_code(200));
    break;

  case 'POST':
    // adds a user

    if ($input === null)
       $_POST;

    // verifies if all the requires parameters were passed
    include_once '../util/validation.php';

    verify_required_fields($input);

    // verifies if the post request came from the valid source
    if ($_SESSION['csrf'] !== $input['csrf']) {
      echo json_encode(['error' => 'Invalid CSRF Token']);
      // echo json_encode(['error' => 'Invalid CSRFToken', 'should' => $_SESSION['csrf']]); // testing
      die(http_response_code(403));
    }

    validate_required_fields($db, $input);

    // check optional values
    $phone_number;
    if (isset($input['phone_number'])) {
      if (!is_phone_number($input['phone_number'])) {
        echo json_encode(['error' => 'Phone number with incorrect format', 'format' => $regex_phone_number]);
        die(http_response_code(400));
      }

      $phone_number = $input['phone_number'];
    }

    $description;
    if (isset($input['description'])) {
      if (!is_profile_description($input['description'])) {
        echo json_encode(['error' => 'Description with incorrect format', 'format' => $regex_profile_description]);
        die(http_response_code(400));
      }

      $description = $input['description'];
    }

    $profile_picture;
    if (isset($input['profile_picture']))
      $profile_picture = $input['profile_picture'];

    $cover_picture;
    if (isset($input['$cover_picture']))
      $cover_picture = $input['$cover_picture'];

    $location;
    if (isset($input['location'])) {
      if (!is_location($input['location'])) {
        echo json_encode(['error' => 'Location with incorrect format', 'format' => $regex_location]);
        die(http_response_code(400));
      }

      $location = $input['location'];
    }

    $birth_date;
    if (isset($input['birth_date'])) {
      if (!is_date($input['birth_date'])) {
        echo json_encode(['error' => 'Birth date with incorrect format', 'format' => $regex_date]);
        die(http_response_code(400));
      }

      $birth_date = $input['birth_date'];
    }

    // inserts the user in the database
    if (!insertUser($db, $input['email'], $input['username'], $input['password'], $input['first_name'], $input['last_name'])) {
      echo json_encode(['error' => 'Couldn\'t register']);
      die(http_response_code(400));
    }

    $user = getUserInfo($db, $input['email']);

    if (!isset($phone_number)) $phone_number = $user['phone_number'];
    if (!isset($description)) $description = $user['description'];
    if (!isset($profile_picture)) $profile_picture = $user['profile_picture'];
    if (!isset($cover_picture)) $cover_picture = $user['cover_picture'];
    if (!isset($location)) $location = $user['location'];
    if (!isset($birth_date)) $birth_date = $user['birth_date'];

    if (!updateUser(
      $db,
      $input['email'],
      $input['username'],
      $input['first_name'],
      $input['last_name'],
      $phone_number,
      $description,
      $profile_picture,
      $cover_picture,
      $location,
      $birth_date
    )) {
      echo json_encode(['error' => 'Couldn\'t add additional parameters']);
      die(http_response_code(400));
    }

    echo json_encode(['message' => 'Registered']);
    die(http_response_code(200));
    break;

  default:
    echo json_encode(['error' => $_SERVER['REQUEST_METHOD'] . ' not allowed', 'allowed' => ['GET', 'POST']]);
    die(http_response_code(403));
    break;
}

function verify_required_fields($input)
{
  $fields = [
    'email',
    'username',
    'first_name',
    'last_name',
    'password',
    'csrf'
  ];

  $missing_field = has_fields($input, $fields);
  if (!$missing_field[0]) {
    echo json_encode(['error' => 'Missing ' . $missing_field[1] . ' value', 'required' => $fields, 'optional' => ['phone_number', 'description', 'profile_picture', 'cover_picture', 'location', 'birth_date']]);
    die(http_response_code(400));
  }
}

function validate_required_fields($db, $input)
{
  // verifies if the email already exists
  if (emailExists($db, $input['email'])) {
    echo json_encode(['error' => 'Email already exists']);
    die(http_response_code(400));
  }

  // verifies if the username already exists
  if (usernameExists($db, $input['username'])) {
    echo json_encode(['error' => 'Username already exists']);
    die(http_response_code(400));
  }

  // verify values
  include_once '../../database/validation.php';

  if (!is_email($input['email'])) {
    echo json_encode(['error' => 'Email with incorrect format', 'format' => $regex_email]);
    die(http_response_code(400));
  }

  if (!is_username($input['username'])) {
    echo json_encode(['error' => 'Username with incorrect format', 'format' => $regex_username]);
    die(http_response_code(400));
  }

  if (!is_password($input['password'])) {
    echo json_encode(['error' => 'Password with incorrect format', 'format' => $regex_password]);
    die(http_response_code(400));
  }

  if (!is_name($input['first_name'])) {
    echo json_encode(['error' => 'First name with incorrect format', 'format' => $regex_name]);
    die(http_response_code(400));
  }

  if (!is_name($input['last_name'])) {
    echo json_encode(['error' => 'Last name with incorrect format', 'format' => $regex_name]);
    die(http_response_code(400));
  }
}
