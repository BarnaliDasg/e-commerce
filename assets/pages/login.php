<?php
$email = isset($_SESSION['old_email']) ? htmlspecialchars($_SESSION['old_email']) : '';
$password = isset($_SESSION['old_password']) ? htmlspecialchars($_SESSION['old_password']) : '';
?>

<?php if (!empty($email) || !empty($password)): ?>
    <div style="width: 70%; margin: 20px auto; background-color: #111; border: 1px solid gold; padding: 15px; color: gold; border-radius: 10px;">
        <strong>Submitted Email:</strong> <?= $email ?><br>
        <strong>Submitted Password:</strong> <?= $password ?>
    </div>
<?php endif; ?>


<div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: gold;">
    <form method="post" action="assets/php/actions.php?login"
        style="background: #111; width: 70%; max-width: 600px; border: solid gold 0.2px; border-radius: 10px; padding: 20px; color: gold;">
        
        <div class="text-center mb-3" style="color: gold;">
            <img src="assets/images/abc.png" alt="Logo" style="height: 100px;">
            <h3 class="mt-2" style="color: gold;">Login</h3>
        </div>

        <!-- Email Input -->
        <div class="form-floating mb-3">
            <input type="text" name="email" class="form-control rounded-0" id="email"
                value="<?= showFormData('email') ?: $email ?>"
                placeholder="Email address"
                style="background-color: #222; border: 1px solid gold; color: gold;">
            <label for="email" style="color: gold;">Email address</label>
            <?= showError('email') ?>
        </div>

        <!-- Password Input -->
        <div class="form-floating mb-3">
            <input type="password" name="password" class="form-control rounded-0" id="password"
                placeholder="Password"
                value="<?= $password ?>" 
                style="background-color: #222; border: 1px solid gold; color: gold;">
            <label for="password" style="color: gold;">Password</label>
            <?= showError('password') ?>
        </div>

        <!-- Display login errors -->
        <div style="color: gold; font-weight: bold;">
            <?= showError('checkUser') ?>
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn btn-primary mt-4" style="width: 100%; ">Login</button>

        <!-- Links -->
        <div class="text-center mt-3" style="color: gold;">
            <a href="?signup" class="text-decoration-none me-3" style="color: gold;">Create New Account</a> |
            <a href="?forgotpassword&newfp" class="text-decoration-none ms-3" style="color: gold;">Forgot password?</a>
        </div>
    </form>
</div>

<?php
// Clear session variables after displaying
unset($_SESSION['old_email']);
unset($_SESSION['old_password']);
?>
