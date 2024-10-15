<?php

// Block unverified users from logging in
function unluckytech_block_unverified_users($user, $password) {
    // Check if the user exists and is not an administrator
    if (!is_wp_error($user) && !current_user_can('administrator', $user->ID)) {
        // Get the user's email_verified status
        $email_verified = get_user_meta($user->ID, 'email_verified', true);

        // If the email is not verified, block the login
        if ($email_verified != 1) {
            return new WP_Error('email_not_verified', __('ERROR: Your email is not verified. Please check your inbox for the verification link.'));
        }
    }

    return $user;
}
add_filter('wp_authenticate_user', 'unluckytech_block_unverified_users', 10, 2);

// Hook into the authenticate process to track login attempts
add_filter('wp_authenticate_user', 'custom_check_login_attempts', 99, 2);
function custom_check_login_attempts($user, $password) {
    if (is_wp_error($user)) {
        return $user; // Return error if user is invalid
    }
    
    // Get user ID
    $user_id = $user->ID;
    
    // Get login attempts meta
    $attempts = get_user_meta($user_id, '_failed_login_attempts', true);
    $last_attempt = get_user_meta($user_id, '_last_failed_login', true);

    // Calculate lockout time (in seconds)
    $lockout_time = custom_calculate_lockout_time($attempts);

    // Check if the user is locked out
    if (!empty($last_attempt) && (time() - $last_attempt) < $lockout_time) {
        $remaining_time = $lockout_time - (time() - $last_attempt);
        return new WP_Error('too_many_attempts', "Too many login attempts. Please try again in " . human_time_diff(time(), time() + $remaining_time) . ".");
    }

    return $user;
}

// Hook into login_failed to increase login attempt counter
add_action('wp_login_failed', 'custom_track_failed_logins', 10, 1);
function custom_track_failed_logins($username) {
    $user = get_user_by('login', $username);

    // If the user exists, increment failed login attempts
    if ($user) {
        $user_id = $user->ID;
        $attempts = get_user_meta($user_id, '_failed_login_attempts', true);
        $attempts = (empty($attempts)) ? 0 : $attempts;
        $attempts++;
        
        // Update the user meta with the new attempt count and timestamp
        update_user_meta($user_id, '_failed_login_attempts', $attempts);
        update_user_meta($user_id, '_last_failed_login', time());
    }
}

// Reset failed login attempts after successful login
add_action('wp_login', 'custom_reset_failed_logins', 10, 2);
function custom_reset_failed_logins($user_login, $user) {
    delete_user_meta($user->ID, '_failed_login_attempts');
    delete_user_meta($user->ID, '_last_failed_login');
}

// Function to calculate lockout time based on number of attempts
function custom_calculate_lockout_time($attempts) {
    $timeout = 0;

    if ($attempts >= 5 && $attempts < 10) {
        $timeout = 2 * MINUTE_IN_SECONDS; // 2 minutes
    } elseif ($attempts >= 10 && $attempts < 15) {
        $timeout = 10 * MINUTE_IN_SECONDS; // 10 minutes
    } elseif ($attempts >= 15 && $attempts < 20) {
        $timeout = HOUR_IN_SECONDS; // 1 hour
    } elseif ($attempts >= 20 && $attempts < 25) {
        $timeout = 12 * HOUR_IN_SECONDS; // 12 hours
    } elseif ($attempts >= 25) {
        $timeout = DAY_IN_SECONDS; // 24 hours
    }

    return $timeout;
}

function disable_admin_bar_on_login($user_login, $user) {
    if (!in_array('administrator', (array) $user->roles)) {
        // Set a session or cookie if necessary
        setcookie('disable_admin_bar', '1', time() + 3600 * 24 * 30, '/'); // expires in 30 days
    }
}
add_action('wp_login', 'disable_admin_bar_on_login', 10, 2);

// Hook into the password reset action to reset failed login attempts
add_action('after_password_reset', 'custom_reset_failed_logins_on_password_reset', 10, 2);
function custom_reset_failed_logins_on_password_reset($user, $new_pass) {
    delete_user_meta($user->ID, '_failed_login_attempts');
    delete_user_meta($user->ID, '_last_failed_login');
}