<?php
include_once './database/connection.php';
include_once './database/users.php';
include_once './database/animals.php';
include_once './database/posts.php';
include_once './database/follows.php';
include_once './database/favorites.php';
include_once './database/images.php';
include_once './database/adoption.php';

$db = getDB();

if (!isset($_GET['id']) || empty($_GET['id'])) {
  if (!isset($_SESSION['email'])) {
    die(header('Location: ./index.php'));
  }

  // redirects to the user's profile in case of /profile
  $user = getUsernameByEmail($db, $_SESSION['email']);
  die(header('Location: profile.php?username=' . $user['username']));
}

$animal_id = $_GET['id'];
$animal = getAnimalById($db, $animal_id);

if (!$animal) {
  die(header('Location: feed.php'));
}

/* if ($user === false) {
  die(header('Location: ./index.php')); // TODO feed or something
} */

$is_favorite = 0;
$user_favorites = getFavorites($db, $_SESSION['email']);
foreach ($user_favorites as $user_favorite) {
  if ($user_favorite['animal'] == $animal_id) {
    $is_favorite = 1;
    break;
  }
}

$fullname = implode(', ', [$animal['name'], $animal['age']]);
$description = $animal['description'];
$profile_pic = getImageURL($animal['photo']);

$owner = getAnimalOwner($db, $animal_id);
$owner_fullname = implode(' ', [$owner['first_name'], $owner['last_name']]);
$posts = getPostsOfAnimal($db, $animal_id);
$questions = getQuestionsOfAnimal($db, $animal_id);

$username = $owner['username'];
$email = $owner['email'];
$phone_number = $owner['phone_number'];
?>

<input name="animalID" type="hidden" value=<?= $animal_id ?>>
<input name="csrf" type="hidden" value=<?= $_SESSION['csrf'] ?>>

<script src="js/templates/animal/animal.js" defer></script>

<main id="animal">
  <div id="profile-top">
    <div id="animal-cover">

    </div>
    <div id="profile-description">
      <div id="profile-image">
        <img src="<?= $profile_pic ?>" alt="profile-picture">
      </div>
      <div id="profile-name">
        <span id="profile-full-name"><?= htmlentities($fullname) ?></span>
      </div>
      <div id="profile-bio">
        <?= htmlentities($description) ?>
      </div>
      <div id="profile-interaction-buttons">
        <div id="animal-interaction-buttons">
          <?php if (isset($_SESSION['email']) && $_SESSION['email'] === $email && (!$animal['listed_for_adoption'] || $animal['listed_for_adoption'] == 'no')) { ?>
            <button id='profile-list-adoption' class="clickable">
              <i class="fas fa-gift"></i>List for adoption
            </button>

          <?php } else if (!(isset($_SESSION['email']) && $_SESSION['email'] === $email)) { ?>
            <button id='profile-contact' class="clickable">
              <i class="fas fa-address-book"></i>Contact Owner
            </button>

            <?php if (!isProposed($db, $_SESSION['email'], $animal_id) && $animal['listed_for_adoption']) { ?>
              <button id='profile-propose-adoption' class="clickable">
                <i class="fas fa-gift"></i>Propose Adoption
              </button>
            <?php } ?>

            <div id="fav-form">
              <?php if ($is_favorite == 1) { ?>
                <form action="./actions/action_unfavorite.php" method="POST" id="unfavorite-form">
                  <input type="hidden" name="animal_id" value="<?= $animal_id ?>" />
                  <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>" />
                  <button type="submit" id='profile-favorite' class="clickable">
                    <i class="fas fa-heart"></i>Favorite
                  <?php } else { ?>
                    <form action="./actions/action_favorite.php" method="POST" id="favorite-form">
                      <input type="hidden" name="animal_id" value="<?= $animal_id ?>" />
                      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>" />
                      <button type="submit" id='profile-favorite' class="clickable">
                        <i class="far fa-heart"></i>Favorite
                      <?php } ?>
                      </button>
                    </form>
                  <?php } ?>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div id="profile-feed">
    <header>
      <ul>
        <li><i class="fas fa-stream"></i>Posts</li>
        <li><i class="fas fa-question"></i>Questions</li>
        <?php if ($owner['email'] === $_SESSION['email']) { ?>
          <li><i class="fas fa-tasks"></i>Proposals</li>
        <?php } ?>
      </ul>
    </header>
    <main>
      <div id="profile-section-posts">

        <div id="profile-posts">
          <?php foreach ($posts as $post) {
            $post['username'] = $username;
            $post['animal_location'] = $animal['location'];
            include 'templates/common/show_post.php';
          } ?>
        </div>
      </div>
      <div id="profile-questions">
        <div id="questions">
          <?php
          if ($_SESSION['email'] != $email) { ?>
            <form method="POST" id="question-form">
              <textarea id="content" name="content" placeholder="Add a question..." required></textarea>
              <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
              <input type="hidden" name="animalID" value=<?= $animal_id ?>>
              <button type="submit">
                <i class="fas fa-paw"></i>
              </button>
            </form>
          <?php }
          foreach ($questions as $question) {
            $user_info = getUserInfo($db, $question['person']);
            $picture_src = getImageURL($user_info['profile_picture']); ?>
            <div id="question">
              <section id="header">
                <span id="username">
                  <a href="profile.php?username=<?= $user_info['username'] ?>">@<?= $user_info['username'] ?></a>
                </span>
                <span id="date">
                  <?= getTimePassed($question['created_at']) ?> ago
                </span>
              </section>
              <section id="body">
                <a href="profile.php?username=" <?= $user_info['username'] ?>>
                  <img src="<?= $picture_src ?>" alt="<?= $user_info['username'] ?>">
                </a>
                <p id="content">
                  <?= htmlentities($question['content']) ?>
                </p>
              </section>
            </div>
            <?php
            $response = getResponseToQuestion($db, $question['id']);
            if ($response) { ?>
              <div id="response">
                <section id="header">
                  <span id="username">
                    <a href="profile.php?username=<?= ($owner['username']) ?>">@<?= $owner['username'] ?></a>
                  </span>
                  <span id="date">
                    <?= getTimePassed($response['created_at']) ?> ago
                  </span>
                </section>
                <section id="body">
                  <a href="profile.php?username=" <?= $owner['username'] ?>>
                    <img src="<?= $picture_src ?>" alt="<?= $owner['username'] ?>">
                  </a>
                  <p id="content">
                    <?= htmlentities($response['content']) ?>
                  </p>
                </section>
              </div>

            <?php } else if ($_SESSION['email'] == $email) { ?>
              <form method="POST" id="responseToQuestionForm">
                <textarea id="content" name="content" placeholder="Add a response..." required></textarea>
                <input type="hidden" name="questionID" value=<?= $question['id'] ?>>
                <button type="submit">
                  <i class="fas fa-paw"></i>
                </button>
              </form>
            <?php } ?>
          <?php } ?>
        </div>

      </div>
      <?php if ($owner['email'] === $_SESSION['email']) { ?>
        <div id="profile-proposals">
          <?php
          $proposals = getAnimalActiveProposals($db, $animal_id);
          foreach ($proposals as $proposal) { ?>
            <div class="proposal-result">
              <div class="search-image">
                <img src="<?= getImageURL($proposal['profile_picture']) ?>" alt="user-photo">
              </div>
              <div class="search-description">
                <header>
                  <h4><a href="profile.php?username=<?= $proposal['email']   ?>">@<?= $proposal['username'] ?></a></h4>
                </header>
              </div>
              <div class="adoption-answers">
                <a href="#" data-email="<?= $proposal['email'] ?>" class="accept clickable"><i class="fas fa-check-square"></i> Accept</a>
                <a href="#" data-email="<?= $proposal['email'] ?>" class="refuse clickable"><i class="fas fa-times-circle"></i> Refuse</a>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
  </div>

  <div id="contact-modal-wrapper" class="modal-wrapper">
    <div id="show-contact" class="modal">
      <header>
        <h2> <?= htmlentities($owner_fullname) ?></h2>
        <button><i class="fas fa-times-circle"></i></button>
      </header>
      <main>
        <p>
          <b>Email:</b> <?= htmlentities($owner['email']) ?><br>
          <?php if ($owner['phone_number']) { ?>
            <b>Phone Number:</b> <?= htmlentities($owner['phone_number']) ?>
          <?php } ?><br>
          <b>Profile:</b><a href="<?= './profile.php?username=' .
                                    $owner['username'] ?>"><?php echo ' @' .
                                                              $owner['username']; ?></a><br>
        </p>
      </main>
    </div>
  </div>
</main>
<script async src="js/templates/profile/sections.js"></script>
<?php if (isset($_SESSION['email']) && $_SESSION['email'] !== $email) { ?>
  <script async>
    document.querySelector('#profile-contact').onclick = () => makeModal('#contact-modal-wrapper', '#show-contact',
      '#show-contact header button')
  </script>
<?php } ?>