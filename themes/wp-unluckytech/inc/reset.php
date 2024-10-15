<?php
// reset.php - Resets failed login attempts and lockout time

function reset_failed_login_attempts($user_id) {
    // Check if the user ID is valid
    if ($user_id) {
        // Reset the failed login attempts and lockout time for the user
        delete_user_meta($user_id, '_failed_login_attempts');
        delete_user_meta($user_id, '_last_failed_login');

        // Log the reset for debugging (optional)
        error_log("Reset failed login attempts and lockout for user ID {$user_id}");
    }
}