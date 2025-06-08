<?php

// Determine the action based on session variable
if (isset($_SESSION['forgot_code']) && !isset($_SESSION['auth_temp'])) {
    $action = 'verify';
} elseif (isset($_SESSION['forgot_code']) && isset($_SESSION['auth_temp'])) {
    $action = 'changepassword';
} else {
    $action = 'forgotpassword';
}
?>

<div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: gold;">
    <form method="post" action="assets/php/actions.php?<?= $action ?>" style="background: #111; width: 70%; max-width: 600px; border: solid gold 0.2px; border-radius: 10px; padding: 20px; color: gold;">
        <h3 class="text-center mb-4" style="color: gold;">
            <?php 
                if ($action == 'forgotpassword') echo "Forgot Your Password?";
                elseif ($action == 'verify') echo "Verify Code";
                else echo "Change Password";
            ?>
        </h3>

        <?php if ($action == 'forgotpassword') { ?>
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control rounded-0" id="floatingInput" placeholder="Enter your email" required
                    style="background-color: #222; border: 1px solid gold; color: gold;">
                <label for="floatingInput" style="color: gold;">Enter your email</label>
            </div>
            <?= showError('email') ?>
            <button type="submit" style="width: 100%; background-color: gold; border-color: gold; color: black; font-weight: 600;" class="btn mt-3">Send Verification Code</button>
        <?php } ?>

        <?php if ($action == 'verify') { ?>
            <p class="text-center" style="color: gold;">Enter the 6-digit code sent to <strong><?= htmlspecialchars($_SESSION['forgot_email']) ?></strong></p>
            <div class="form-floating mb-3">
                <input type="text" name="verification_code" class="form-control rounded-0" id="floatingCode" placeholder="######" maxlength="6" required
                    style="background-color: #222; border: 1px solid gold; color: gold;">
                <label for="floatingCode" style="color: gold;">Enter OTP</label>
            </div>
            <?= showError('email_verify') ?>
            <!-- <p class="text-center text-danger" style="color: gold;">Your verification code: <strong><?= htmlspecialchars($_SESSION['forgot_code']) ?></strong></p> -->
            <button type="submit" style="width: 100%; background-color: gold; border-color: gold; color: black; font-weight: 600;" class="btn mt-3">Verify Code</button>
        <?php } ?>

        <?php if ($action == 'changepassword') { ?>
            <p class="text-center" style="color: gold;">Set a new password for <strong><?= htmlspecialchars($_SESSION['forgot_email']) ?></strong></p>
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control rounded-0" id="floatingNewPassword" placeholder="New Password" required
                    style="background-color: #222; border: 1px solid gold; color: gold;">
                <label for="floatingNewPassword" style="color: gold;">New Password</label>
            </div>
            <?= showError('password') ?>
            <button type="submit" style="width: 100%;">Change Password</button>
        <?php } ?>

        <div class="text-center mt-4">
            <a href="?login" class="text-decoration-none" style="color: gold;">
                <i class="bi bi-arrow-left-circle-fill"></i> Go Back To Login
            </a>
        </div>
    </form>
</div>
