<?php

/**
 * DEPRECATED, API IN USE
 */

include_once '../templates/common/session_start.php';

$_SESSION = array();

session_destroy();
header("Location: ../index.php");
