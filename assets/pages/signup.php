<div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: black; color: gold; padding: 20px;">
  <div style="background: #111; width: 70%; max-width: 650px; border: 0.2px solid gold; border-radius: 10px; padding: 30px; box-sizing: border-box; color: gold; box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);">
    <form method="post" action="assets/php/actions.php?signup" style="width: 100%;">

      <div style="text-align: center; margin-bottom: 25px;">
        <img src="assets/images/abc.png" alt="Logo" style="height: 100px; margin-bottom: 10px;">
        <h3 style="margin: 0; font-weight: 600;">Create New Account</h3>
      </div>


      <!-- Name fields -->
      <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <div style="flex: 1;">
          <label for="fname" style="display: block; margin-bottom: 6px; font-weight: 600;">First Name</label>
          <input type="text" name="fname" id="fname" placeholder="First Name" value="<?= showFormData('fname') ?>"
            style="width: 100%; padding: 10px 12px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
          <?= showError('fname') ?>
        </div>
        <div style="flex: 1;">
          <label for="lname" style="display: block; margin-bottom: 6px; font-weight: 600;">Last Name</label>
          <input type="text" name="lname" id="lname" placeholder="Last Name" value="<?= showFormData('lname') ?>"
            style="width: 100%; padding: 10px 12px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
          <?= showError('lname') ?>
        </div>
      </div>

      <!-- Email and Username -->
      <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <div style="flex: 1;">
          <label for="email" style="display: block; margin-bottom: 6px; font-weight: 600;">Email Address</label>
          <input type="email" name="email" id="email" placeholder="Email Address" value="<?= showFormData('email') ?>"
            style="width: 100%; padding: 10px 12px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
          <?= showError('email') ?>
        </div>
      </div>

      <!-- Password -->
      <div style="margin-bottom: 20px;">
        <label for="password" style="display: block; margin-bottom: 6px; font-weight: 600;">Password</label>
        <input type="password" name="password" id="password" placeholder="Password"
          style="width: 100%; padding: 10px 12px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
        <?= showError('password') ?>
      </div>

      <button type="submit" style="width: 100%;">
        Submit
      </button>

      <div style="text-align: center; margin-top: 15px;">
        <a href="?login" style="color: gold; text-decoration: none; font-weight: 600;">Already have an account?</a>
      </div>

    </form>
  </div>
</div>
