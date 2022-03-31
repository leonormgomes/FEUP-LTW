<div id="animals">

  <?php
  // an array named $animals is required to run this

  if (isset($animals)) {
    foreach ($animals as $animal) { ?>

      <div class="animal clickable" onclick="location.href='animal.php?id=<?= $animal['id'] ?>';">
        <div><?= htmlentities($animal['name']) ?></div>
        <img src="<?= getImageURL($animal['photo']) ?>" alt="<?= $animal['name'] ?>">
      </div>

  <?php }
  }
  ?>

</div>