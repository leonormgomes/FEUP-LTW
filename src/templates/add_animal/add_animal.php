<?php
include_once './database/connection.php';
include_once './database/users.php';
include_once './database/posts.php';
include_once './database/images.php';

$db = getDB();
?>

<header>
  <?php include 'templates/common/navbar.php'; ?>
</header>
<main id="add-animal">
  <div id="form-wrapper">
    <h2>Create an animal page for your pet or list an animal</h2>
    <form>
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
      <div id="animal-photo">
        <label>
          Upload a picture...
          <input type="file" name="photo" accept=".jpg, .jpeg, .png">
        </label>
      </div>
      <label>
        Name<input type="text" name="name" required pattern=".*[^\s]{1}.*" placeholder="Fluffly Dog">
      </label>
      <fieldset id="age-type">
        <label>
          Age<input type="number" name="age" required pattern=".*[^\s]{1}.*" placeholder="4">
        </label>
        <label data-age-type>
          Months<input type="radio" name="age_type" required value="months">
        </label>
        <label data-age-type>
          Years<input type="radio" name="age_type" required value="years">
        </label>
      </fieldset>
      <label>
        Size<input type="text" name="size" required pattern=".*[^\s]{1}.*" placeholder="87cm">
      </label>
      <label>
        Species<input type="text" name="species" required pattern=".*[^\s]{1}.*" placeholder="Dog (golden retriever)">
      </label>
      <fieldset>
        <legend>Listed for adoption</legend>
        <label>
          Yes, I want to list this animal for adoption<input type="radio" name="listed" required value="1">
        </label>
        <label>
          No, I own this animal<input type="radio" name="listed" required value="0">
        </label>
      </fieldset>
      <label>
        Location<input type="text" name="location" required pattern=".*[^\s]{1}.*" placeholder="Birchwood Paddock">
      </label>
      <label for="animal-description">
        Description
      </label>
      <textarea id="animal-description" name="description" rows="2" placeholder="Very lovable and cute"></textarea>
      <input type="submit">
      <label id="feedback">

      </label>
    </form>
  </div>
</main>
<script src="js/templates/add_animal/image.js"></script>
<script async src="js/templates/add_animal/form.js"></script>