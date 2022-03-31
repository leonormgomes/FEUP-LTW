<?php

include 'templates/common/session_start.php';

if (isset($_SESSION['email']))
  header('Location: feed.php');

include 'templates/common/head.php';
include './templates/index/index_page.php';
  //falta dar include ao footer
