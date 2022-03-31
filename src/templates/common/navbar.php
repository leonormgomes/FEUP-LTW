<?php
include_once './database/connection.php';

if (!isset($_SESSION['email']))
  header('Location: index.php');

include_once './database/users.php';
include_once './database/images.php';

$db = getDB();

$user_navbar = getUserInfo($db, $_SESSION['email']);
$fullname = implode(' ', [
  $user_navbar['first_name'],
  $user_navbar['last_name'],
]);
$profile_pic = getImageURL($user_navbar['profile_picture']);
?>

<nav id="navbar">
  <div id="navbar-profile">
    <a href="profile.php">
      <img src="<?= $profile_pic ?>" alt="profile-picture">
      <span><?= $fullname ?></span>
    </a>
    <a href="feed.php" class="nav-button clickable">
      <i class="fas fa-comments"></i> Feed
    </a>
  </div>
  <div id="navbar-search">
    <form method="GET" action="./search.php">
      <input type="search" name="search" aria-label="Search through site content" placeholder="Search here">
    </form>
  </div>
  <div id="navbar-buttons">
    <a href="actions/action_logout.php" id="logout" class="clickable">
      Logout <i class="fas fa-sign-out-alt"></i>
    </a>
  </div>
</nav>

<nav id="navbar-mobile">
  <div id="navbar-profile">
    <a href="profile.php">
      <img src="<?= $profile_pic ?>" alt="profile-picture">
    </a>
    <a href="feed.php" class="nav-button clickable">
      <i class="fas fa-comments"></i>
    </a>
    <div id="navbar-search">
      <a href="search.php?search=" id="logout" class="clickable">
        <i class="fas fa-search"></i>
      </a>
    </div>
    <div id="navbar-buttons">
      <a href="actions/action_logout.php" id="logout" class="clickable">
        <i class="fas fa-sign-out-alt"></i>
      </a>
    </div>
  </div>
</nav>

<script src="js/templates/common/navbar.js"></script>