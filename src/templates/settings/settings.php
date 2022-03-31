<script defer src="js/templates/settings/form.js"></script>
<?php include_once 'database/validation.php' ?>

<main id="settings">
  <form action="actions/profile/action_update_profile.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <h1 id="settings_header" class="header">Settings</h1>
    <h2 id="profile_header" class="header">Profile</h2>
    <div id="profile_divider" class="divider"></div>
    <fieldset id="profile_form">
      <label>
        <img src=<?= getImageURL($user['cover_picture']) ?> alt="Profile cover" id="cover">
        <input type="file" id="cover_input" name="cover_picture" accept=".jpg, .jpeg, .png">
      </label>
      <label>
        <div id="profile_photo">
          <img src=<?= getImageURL($user['profile_picture']) ?> alt="Profile photo" id="profile_photo">
        </div><input type="file" id="profile_photo_input" name="profile_picture" accept=".jpg, .jpeg, .png">
      </label>
      <fieldset id="profile_info">
        <label>
          First name<input type="text" name="first_name" placeholder="---" value="<?= $user['first_name'] ?>" pattern="<?= $regex_name ?>" required>
        </label>
        <label>
          Last name<input type="text" name="last_name" placeholder="---" value="<?= $user['last_name'] ?>" pattern="<?= $regex_name ?>" required>
        </label>
        <label>
          Description<input type="text" name="description" placeholder="---" value='<?= $user['description'] ?>'>
        </label>
      </fieldset>
    </fieldset>

    <h2 id="account_header" class="header">Account</h2>
    <div id="account_divider" class="divider"></div>
    <fieldset id="account_form">
      <label id="username">
        Username<input type="text" name="username" placeholder="---" value="<?= $user['username'] ?>" pattern="<?= $regex_username ?>" required>
      </label>
      <label>
        Location<input type="text" name="location" placeholder="---" value="<?= $user['location'] ?>" pattern="<?= $regex_location ?>">
      </label>
      <label id="phone_number">
        Phone number<input type="tel" name="phone_number" placeholder="---" value="<?= $user['phone_number'] ?>">
      </label>
      <label>
        Birth date<input type="date" name="birth_date" value="<?= $user['birth_date'] ?>">
      </label>
    </fieldset>

    <div id="submit">
      <input type="submit" value="Save settings" name="submit">

    </div>
  </form>
</main>