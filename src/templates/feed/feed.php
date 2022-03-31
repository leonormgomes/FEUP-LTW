<?php

if (!isset($_SESSION['email'])) {
  die(header('Location: index.php'));
}

if (!isset($_GET['page']) || ($_GET['page'] != 'timeline' && $_GET['page'] != 'explore')) {
  die(header('Location: feed.php?page=explore'));
}

include_once './database/connection.php';
include_once './database/users.php';
include_once './database/posts.php';
include_once './database/images.php';

$db = getDB();
$user = getUserInfo($db, $_SESSION['email']);
?>

<header>
  <?php
  include 'templates/common/navbar.php';
  ?>
</header>
<main id="feed">
  <?php
  include 'templates/feed/menu.php';

  if ($_GET['page'] == 'explore') {
    include_once __DIR__ . '/explore.php';
  } else if ($_GET['page'] == 'timeline') {
    include_once __DIR__ . '/timeline.php';
  } ?>
</main>