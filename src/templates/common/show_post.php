<?php $post_date =
  substr(date('F', strtotime($post['created_at'])), 0, 3) .
  ' ' .
  date('d', strtotime($post['created_at'])); ?>

<?php $id = $post['id'] ?>

<div class="profile-post" onclick="location.href='post.php?post=<?= $id ?>';">
  <div class="profile-post-image" onclick="location.href='animal.php?id=<?= $post['animal'] ?>'; window.event.stopPropagation();">
    <img src="<?= getImageURL($post['photo']) ?>" alt="post-image">
  </div>
  <div class="profile-post-description">
    <span class="profile-post-username"><a href="profile.php?username=<?= $post['username'] ?>"><?= $post['username'] ?></a></span>
    <span class=" profile-post-date"><?= $post_date ?></span>
    <span class="profile-post-location"><?= $post['animal_location'] ?></span>
  </div>
  <div class="profile-post-content">
    <h3><?= htmlentities($post['title']) ?></h3>
    <p>
      <?= htmlentities($post['content']) ?>
    </p>
  </div>
  <div class="profile-post-description">
    <span class="profile-post-username"><a href="profile.php?username=<?= $post['username'] ?>"><?= $post['username'] ?></a></span>
    <span class=" profile-post-date"><?= htmlentities($post_date) ?></span>
    <span class="profile-post-location"><?= htmlentities($post['animal_location']) ?></span>
  </div>

  <!-- Delete button -->
  <?php if (isset($_SESSION['email']) && $_SESSION['email'] == $post['person']) { ?>
    <form action="actions/action_delete_post.php" method="POST" class="delete-post-form">
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
      <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
      <button type="submit" class="delete-post-button">
        <i class="far fa-trash-alt"></i>
      </button>
    </form>
  <?php } ?>
</div>