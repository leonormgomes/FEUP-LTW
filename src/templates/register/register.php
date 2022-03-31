<script defer src="js/templates/register/register.js"></script>
<?php include_once 'database/validation.php' ?>

<div id="login_landing_page">
  <div id="register_page_background" class="page_background"></div>
  <div id="image_register" class="front_image"></div>

  <h1>Sign up</h1>

  <div id="auth_form">

    <form action="actions/action_register.php" method="POST" id="register_form">
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
      <div id="names">
        <label>
          First name <input type="text" name="first_name" placeholder="fluffy" required>
        </label>
        <label>
          Last name <input type="text" name="last_name" placeholder="dog" required>
        </label>
      </div>
      <label>
        Username <input type="text" name="username" placeholder="fluffydoggy" required>
      </label>
      <label>
        Email <input type="email" name="email" placeholder="fluffydoggy@gmail.com" required>
      </label>
      <div id="passwords">
        <label>
          Password <input type="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
        </label>
        <label>
          Confirm Password <input type="password" name="confirm_password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
        </label>
      </div>
      <button type="submit" form="register_form">
        <i class="fas fa-sign-in-alt"></i>
      </button>
    </form>

    <div id="login_extra">
      <div id="login_instead">
        <p>
          <a href="login.php">Already have an account</a>
        </p>
      </div>
      <div id="error_message"></div>
    </div>
  </div>

</div>