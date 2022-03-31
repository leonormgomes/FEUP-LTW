<?php
include_once './database/connection.php';
include_once './database/users.php';
include_once './database/posts.php';
include_once './database/follows.php';
include_once './database/images.php';
include_once './database/favorites.php';

$db = getDB();

if (!isset($_GET['username']) || empty($_GET['username'])) {
  if (!isset($_SESSION['email'])) {
    die(header('Location: ./index.php'));
  }

  // redirects to the user's profile in case of /profile
  $user = getUserInfo($db, $_SESSION['email']);
  die(header('Location: profile.php?username=' . $user['username']));
}

$user = getUserByUsername($db, $_GET['username']);

if ($user === false) {
  die(header('Location: ./index.php')); // TODO feed or something
}

$username = $user['username'];
$email = $user['email'];
$fullname = implode(' ', [$user['first_name'], $user['last_name']]);
$description = $user['description'];
$phone_number = $user['phone_number'];
$profile_pic = getImageURL($user['profile_picture']);
$profile_cover = getImageURL($user['cover_picture']);

$posts = getPostsFromUser($db, $email);
$pets = animalsofUser($db, $email);
$following = getFollowing($db, $email);
$followers = getFollowers($db, $email);
?>

<input type="hidden" name="username" value="<?= $username ?>">

<script defer src="js/templates/post/show_post.js"></script>

<main id="profile">
  <div id="profile-top">
    <div id="profile-cover">
      <img src="<?= $profile_cover ?>" alt="profile-cover">
    </div>
    <div id="profile-description">
      <div id="profile-image">
        <img src="<?= $profile_pic ?>" alt="profile-picture">
      </div>
      <div id="profile-name">
        <span id="profile-full-name"><?= htmlentities($fullname) ?></span>
        <span id="profile-username-at"><?= htmlentities($username) ?></span>
      </div>
      <div id="profile-bio">
        <?= htmlentities($description) ?>
      </div>
      <div id="profile-interactions">
        <span id="profile-following"><?= count($following) ?></span>
        <span id="profile-followers"><?= count($followers) ?></span>
      </div>
      <div id="profile-interaction-buttons">
        <?php if (
          isset($_SESSION['email']) &&
          $_SESSION['email'] === $email
        ) { ?>
          <a class="clickable" href="add_animal.php">
            <i class="fas fa-folder-plus"></i>Add animal
          </a>
          <a class="clickable" href="settings.php">
            <i class="fas fa-user-cog"></i>Settings
          </a>
        <?php } else { ?>
          <button class="clickable" id='profile-contact'>
            <i class="fas fa-address-book"></i>Contact
          </button>
          <?php if (!checksFollows($db, $_SESSION['email'], $email)) { ?>
            <form action="./actions/action_follow.php" method="POST" id="follow-form">
              <input type="hidden" name="user" value="<?= $email ?>">
              <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
              <button class="clickable" type="submit" data-follow-button><i class="fas fa-user-friends"></i></button>
            </form>
          <?php } else { ?>
            <form action="./actions/action_unfollow.php" method="POST" id="unfollow-form">
              <input type="hidden" name="user" value="<?= $email ?>">
              <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
              <button class="clickable" type="submit" data-following-button><i class="fas fa-user-friends"></i></button>
            </form>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
  <div id="profile-feed">
    <header>
      <ul>
        <li><i class="fas fa-stream"></i>Posts</li>
        <li><i class="fas fa-paw"></i>Pets</li>
        <li><i class="fas fa-heart"></i>Favs</li>
        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === $email) {  ?>
          <li><i class="fas fa-tasks"></i>Proposals</li>
        <?php } ?>
      </ul>
    </header>
    <main>
      <div id="profile-section-posts">
        <?php if (
          isset($_SESSION['email']) &&
          $_SESSION['email'] === $email
        ) { ?>
          <?php include 'templates/common/add_post.php'; ?>
        <?php } ?>
        <div id="profile-posts">
          <?php foreach ($posts as $post) {
            include 'templates/common/show_post.php';
          } ?>
        </div>
      </div>
      <div id="profile-section-pets">
        <?php
        $animals = animalsofUsername($db, $_GET['username']);
        include './templates/animal/show_animals.php';
        ?>
      </div>
      <div id="profile-section-favs">
        <?php
        $animals = getFavoritesOfUsername($db, $_GET['username']);
        include './templates/animal/show_animals.php';
        ?>
      </div>
      <?php if (isset($_SESSION['email']) && $_SESSION['email'] === $email) { ?>
        <div id="profile-section-proposals">
          <h4>Listed</h4>
          <div id="profile-listed">
          </div>
          <h4>Proposals</h4>
          <div id="profile-proposals">
          </div>
        </div>
      <?php } ?>

      <div id="following-modal-wrapper" class="modal-wrapper">
        <div id="show-following" class="modal">
          <header>
            <h2> Following </h2>
            <button><i class="fas fa-times-circle"></i></button>
          </header>
          <main>
            <?php if (empty($following)) {
              echo 'This list appears to be empty...';
            } else {
              foreach ($following as $user) { ?>
                <div>
                  <img class="round" src="<?= getImageURL(
                                            $user['profile_picture']
                                          ) ?>" alt="<?= $user['username'] ?>">
                  <a href='./profile.php?username=<?= $user['username'] ?>'>
                    @<?= $user['username'] ?>
                  </a>
                </div>
            <?php }
            } ?>
          </main>
        </div>
      </div>

      <div id="followers-modal-wrapper" class="modal-wrapper">
        <div id="show-followers" class="modal">
          <header>
            <h2> Followers </h2>
            <button><i class="fas fa-times-circle"></i></button>
          </header>
          <main>
            <?php if (empty($followers)) {
              echo 'This list appears to be empty...';
            } else {
              foreach ($followers as $user) { ?>
                <div>

                  <img class="round" src="<?= getImageURL(
                                            $user['profile_picture']
                                          ) ?>" alt="<?= $user['username'] ?>">
                  <a href='./profile.php?username=<?= $user['username'] ?>'>
                    @<?= $user['username'] ?>
                  </a>
                </div>
            <?php }
            } ?>
          </main>
        </div>
      </div>
      <div id="contact-modal-wrapper" class="modal-wrapper">
        <div id="show-contact" class="modal">
          <header>
            <h2> <?= htmlentities($fullname) ?></h2>
            <button><i class="fas fa-times-circle"></i></button>
          </header>
          <main>
            <p>
              <b>Email:</b> <?= htmlentities($email) ?><br>
              <?php if ($phone_number) { ?>
                <b>Phone Number:</b> <?= htmlentities($phone_number) ?>
              <?php } ?>
            </p>
          </main>
        </div>
      </div>
    </main>
  </div>
</main>
<script async src="js/templates/profile/index.js"></script>
<script async src="js/templates/profile/sections.js"></script>
<?php if (isset($_SESSION['email']) && $_SESSION['email'] !== $email) { ?>
  <script async>
    document.querySelector('#profile-contact').onclick = () => makeModal('#contact-modal-wrapper', '#show-contact',
      '#show-contact header button')
  </script>
<?php } ?>