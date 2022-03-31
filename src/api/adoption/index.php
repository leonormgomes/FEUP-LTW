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
  case 'GET':
    if ($input === null) {
      $input = $_GET;
    }

    if (!isset($_SESSION['email'])) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Invalid session']);
      die(http_response_code(400));
    }

    $listed = getListed($db, $_SESSION['email']);
    $proposals = getProposals($db, $_SESSION['email']);

    echo json_encode(['listed' => $listed, 'proposals' => $proposals]);
    die(http_response_code(200));
    break;

  default:
    echo json_encode([
      'error' => $_SERVER['REQUEST_METHOD'] . ' not allowed',
      'allowed' => ['GET'],
    ]);
    die(http_response_code(403));
    break;
}