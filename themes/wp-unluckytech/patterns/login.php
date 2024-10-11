<?php
/**
 * Title: Login
 * Slug: unluckytech/login
 * Categories: text, featured
 * Description: This would be used for the login page of the website.
 */

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['log']) && !empty($_POST['pwd'])) {
    $creds = array();
    $creds['user_login'] = sanitize_text_field($_POST['log']);
    $creds['user_password'] = sanitize_text_field($_POST['pwd']);
    
    // Set 'remember' based on hidden input for "Remember Me" toggle state
    $creds['remember'] = isset($_POST['rememberme']) && $_POST['rememberme'] === 'true' ? true : false;

    $user = wp_signon($creds, false);

    // Check if the login was successful
    if (is_wp_error($user)) {
        // Set the error message for front-end use
        $login_error = 'Wrong email or password';
    } else {
        // Redirect or refresh the page
        wp_safe_redirect($_SERVER['REQUEST_URI']);
        exit;
    }
}
?>

<div class="user-login-form" id="userLoginForm">
    <?php if (is_user_logged_in()) : 
        $current_user = wp_get_current_user(); ?>
        
        <div class="user-profile">
            <img src="<?php echo get_avatar_url($current_user->ID); ?>" alt="Profile Icon" class="profile-icon">
            <p class="username"><?php echo esc_html($current_user->display_name); ?></p>
            <div class="login-buttons">
                <button type="button" id="accountButton" onclick="location.href='<?php echo esc_url(home_url('/account')); ?>'">Account</button>
                <button type="button" id="logoutButton" onclick="location.href='<?php echo esc_url(wp_logout_url(home_url())); ?>'">Log Out</button>
            </div>
        </div>
        
    <?php else : ?>
        
        <form id="loginForm" method="post" action="">
            <input type="text" id="loginEmail" name="log" placeholder="Email" required />
            <input type="password" id="loginPassword" name="pwd" placeholder="Password" required />
            
            <!-- Remember Me and Forgot Password Buttons -->
            <div class="remember-forgot-container">
                <input type="hidden" id="rememberMeHidden" name="rememberme" value="false" />
                <div class="button-style remember-me" id="rememberMeButton">Remember Me</div>
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="button-style forgot-password">Forgot Password</a>
            </div>

            <div class="login-buttons">
                <button type="submit" name="wp-submit" id="loginButton">Log In</button>
                <button type="button" id="signupButton" onclick="location.href='<?php echo wp_registration_url(); ?>'">Sign Up</button>
            </div>

            <!-- Hidden field for error message -->
            <input type="hidden" id="loginError" value="<?php echo isset($login_error) ? esc_html($login_error) : ''; ?>" />
        </form>
        
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rememberMeButton = document.getElementById('rememberMeButton');
        var rememberMeHidden = document.getElementById('rememberMeHidden');
        var loginError = document.getElementById('loginError').value;

        // Toggle button highlight when clicked (text color only)
        rememberMeButton.addEventListener('click', function () {
            if (rememberMeHidden.value === 'false') {
                rememberMeButton.style.color = '#f0a500'; // Change text color to highlight
                rememberMeHidden.value = 'true';
            } else {
                rememberMeButton.style.color = '#fff'; // Revert text color
                rememberMeHidden.value = 'false';
            }
        });

        // Display login error if present
        if (loginError) {
            alert(loginError); // Display the pop-up with the error message
        }
    });
</script>
