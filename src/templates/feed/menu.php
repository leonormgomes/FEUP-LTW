<div id="feed-menu">
  <ul>
    <li class="clickable" <?php if (isset($_GET['page'])) {
      echo 'data-selected=' . ($_GET['page'] == 'explore');
    } ?>><a href="feed.php?page=explore"><i class="fas fa-search-location"></i> Explore</a></li>
    <li class="clickable" <?php if (isset($_GET['page'])) {
      echo 'data-selected=' . ($_GET['page'] == 'timeline');
    } ?>><a href="feed.php?page=timeline"><i class="fas fa-home"></i></i> Timeline</a></li>
  </ul>
</div>