<?php
global $user;
?>

<div class="login d-flex justify-content-center align-items-center" style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; color: gold;">
    
        <form style="background: #111; width: 70%; max-width: 600px; border: solid gold 0.2px; border-radius: 10px; padding: 20px; color: gold;">
            <div class="text-center">
                <img class="mb-4" src="assets/images/abc.png" alt="Company Logo" height="50" >
            </div>
            <h1 class="h5 mb-3 fw-normal text-center">
                Hello, <?= htmlspecialchars($user['fname']) . ' ' . htmlspecialchars($user['lname']) ?> 
                <span class="d-block">(<?= htmlspecialchars($user['email']) ?>)</span>
                <br>Your account has been blocked by the admin.
            </h1>
            <div class="mt-3 text-center">
                <a href="assets/php/actions.php?logout" class="btn btn-danger">Logout</a>
            </div>
        </form>
    
</div>
