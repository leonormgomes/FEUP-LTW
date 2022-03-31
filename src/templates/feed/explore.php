<?php $posts = getAllPosts($db); ?>

<script defer src="js/templates/post/show_post.js"></script>

<div id="explore">
  <?php include_once 'templates/common/add_post.php' ?>

  <div id="profile-posts">
    <?php foreach ($posts as $post) {
      include 'templates/common/show_post.php';
    } ?>
  </div>
</div>