<script defer src="js/templates/login/login.js"></script>
<div id="login_landing_page">
  <div id="login_page_background" class="page_background"></div>
  <div id="image_login" class="front_image"></div>

  <h1>Log in</h1>

  <div id="auth_form">

    <form action="actions/action_login.php" method="POST">
      <label>
        Email <input type="email" name="email" placeholder="fluffydoggy@gmail.com" required>
      </label>
      <label>
        Password <input type="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
      </label>
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
      <button type="submit">
        <i class="fas fa-sign-in-alt"></i>
      </button>
    </form>

    <div id="login_extra">
      <div id="signup">
        <p>
          New user? <a href="register.php">Signup</a>
        </p>
      </div>
      <div id="error_message"></div>
    </div>
  </div>

</div>