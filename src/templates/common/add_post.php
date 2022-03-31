<script defer src="js/templates/add_post/image_preview.js"></script>
<div id="profile-add-post">
  <img src="<?= $profile_pic ?>" alt="add-post-picture">
  <input type="button" value="<?= $user['first_name'] ?>, what is happening?">
</div>

<div id="post-modal-wrapper" class="modal-wrapper">
  <div id="add-post" class="modal">
    <header>
      <h2>Create post</h2>
      <button><i class="fas fa-times-circle"></i></button>
    </header>
    <main id="add-post-main">
      <form id="add-post-form" method="post" enctype="multipart/form-data">
        <div id="image_name_animal">
          <div id="image_name">
            <img id="add-post-profile-pic" dragable="false" src="<?= $profile_pic ?>" alt="profile-picture">
            <?= $fullname ?>
          </div>

          <div id="dropdown">
            <label>Animal:
              <select id="animal" name="animal" required>
                <?php
                $animals = animalsofUser($db, $_SESSION['email']);

                // echo '<option value=NULL> </option>';
                foreach ($animals as $animal) { ?>
                  <option value="<?= $animal['id'] ?>"><?= htmlentities($animal['name']) ?></option>
                <?php } ?>

              </select><br>
            </label>
          </div>
        </div>

        <input type="text" name="title" required placeholder="Title">
        <textarea rows="5" cols="45" name="description" placeholder="What's happening" required></textarea>

        <div id=file-display-area>
        </div>

        <label class="custom-file-upload">
          <input type="file" name="photo" id="fileToUpload" accept="image/*">
          <i class="fa fa-upload"></i> Image to upload
        </label>

        <input type="submit" id="post" name="post">

        </label>

      </form>
    </main>
  </div>
</div>

<script async src="js/templates/add_post/add_post.js"></script>
<script async>
  document.querySelector('#profile-add-post input').onclick = () => makeModal('#post-modal-wrapper', '#add-post', '#add-post header button')
</script>