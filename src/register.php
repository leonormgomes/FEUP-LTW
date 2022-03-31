<?php
include_once 'templates/common/session_start.php';

if (isset($_SESSION['email'])) {
  header('Location: index.php'); // TODO header to feed instead
  die();
}

include 'templates/common/head.php';
include 'templates/register/register.php';