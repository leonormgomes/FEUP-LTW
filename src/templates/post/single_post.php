<?php

if (!isset($_GET['post']) || empty($_GET['post'])) {
  die(header('Location: ./feed.php'));
}

include_once './database/connection.php';
$db = getDB();

include_once './database/posts.php';

$postID = $_GET['post'];
$post = getPostByID($db, $postID);

if (!$post) {
  die(header('Location: feed.php'));
}

include_once './templates/common/show_post.php';

$comments = getPostComments($db, $postID);

include_once './database/users.php';
include_once './database/images.php';
?>

<script defer src="js/templates/post/show_post.js"></script>
<script defer src="js/templates/post/single_post.js"></script>
<h2>
  Comments
</h2>
<form action="actions/action_comment.php" method="POST" id="comment-form">
  <textarea id="content" name="content" placeholder="Add a comment..." required></textarea>
  <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
  <input type="hidden" name="postID" value=<?= $postID ?>>
  <button type="submit">
    <i class="fas fa-paw"></i>
  </button>
</form>
<div id="all-comments">
  <?php
  foreach ($comments as $comment) {
    $user_info = getUserInfo($db, $comment['person']);
    $picture_src = getImageURL($user_info['profile_picture']);
  ?>

    <div id="comment">
      <section id="header">
        <span id="username">
          <a href="profile.php?username=<?= $user_info['username'] ?>">@<?= $user_info['username'] ?></a>
        </span>
        <span id="date">
          <?= getTimePassed($comment['created_at']) ?> ago
        </span>
      </section>
      <section id="body">
        <a href="profile.php?username=" <?= $user_info['username'] ?>>
          <img src="<?= $picture_src ?>" alt="<?= $user_info['username'] ?>">
        </a>
        <p id="content">
          <?= htmlentities($comment['content']) ?>
        </p>
      </section>
    </div>

  <?php } ?>
</div>