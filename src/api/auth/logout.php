<?php

// initializes the session
include_once '../../templates/common/session_start.php';

// verifies the request method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  echo json_encode(['error' => $_SERVER['REQUEST_METHOD'] . ' not allowed', 'allowed' => ['POST']]);
  die(http_response_code(403));
}

// verifies if the user is already logged out
if (!isset($_SESSION['email'])) {
  echo json_encode(['error' => 'Already logged out']);
  die(http_response_code(403));
}

// gets the email to logout
$email = $_SESSION['email'];

// destroys the session
$_SESSION = array();

session_destroy();
echo json_encode(['message' => 'Logged out', 'email' => $email]);
die(http_response_code(200));
