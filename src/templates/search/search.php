<?php
include_once './database/connection.php';

$db = getDB();
?>

<main id="search">
  <div id="navbar-search">
    <form method="GET" action="./search.php">
      <input type="search" name="search" aria-label="Search through site content" placeholder="Search here">
    </form>
  </div>
  <?php include 'templates/feed/menu.php'; ?>
  <div id="search-list">
    <div id="search-animals">
      <h4>Animals</h4>
      <label id="listed-checkbox"><span>Filter by</span> Listed for
        adoption <input type="checkbox" name="listed"></label>
      <div>
      </div>
    </div>
    <div id="search-users">
      <h4>Users</h4>
      <div>
      </div>
    </div>
  </div>
</main>
<script async src="js/templates/search/index.js"></script>