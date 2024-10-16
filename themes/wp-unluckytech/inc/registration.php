<?php

function custom_login_logo() {
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/BizTitleLite.webp);
            padding-bottom: 30px;
            background-size: contain;
            width: 100%;
            height: 80px;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'custom_login_logo');

// Enqueue custom login styles
function unluckytech_login_styles() {
    wp_enqueue_style('login-css', get_template_directory_uri() . '/assets/css/login.css');
    wp_enqueue_script('particle-animation-js', get_template_directory_uri() . '/assets/js/particle-animation.js', array('jquery'), null, true);
    
    add_action('login_footer', function() {
        ?>
        <div class="particle-network-animation">
            <div class="glow glow-1"></div>
            <div class="glow glow-2"></div>
            <div class="glow glow-3"></div>
            <canvas id="particle-canvas"></canvas>
        </div>
        <?php
    });
}
add_action('login_enqueue_scripts', 'unluckytech_login_styles');

// Add custom password fields to the registration form
function unluckytech_registration_password_fields() {
    ?>
    <p>
        <label for="password"><?php _e('Password') ?><br />
        <input type="password" name="password" id="password" class="input" value="<?php if (!empty($_POST['password'])) echo esc_attr($_POST['password']); ?>" size="25" /></label>
    </p>
    <p>
        <label for="confirm_password"><?php _e('Confirm Password') ?><br />
        <input type="password" name="confirm_password" id="confirm_password" class="input" value="<?php if (!empty($_POST['confirm_password'])) echo esc_attr($_POST['confirm_password']); ?>" size="25" /></label>
    </p>
    <?php
}
add_action('register_form', 'unluckytech_registration_password_fields');

// Validate password fields
function unluckytech_validate_password_fields($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $errors->add('password_error', __('<strong>ERROR</strong>: You must include both password and confirm password.'));
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $errors->add('password_mismatch', __('<strong>ERROR</strong>: Passwords do not match.'));
    }
    return $errors;
}
add_filter('registration_errors', 'unluckytech_validate_password_fields', 10, 3);

// Save password on registration
function unluckytech_save_password_and_send_verification($user_id) {
    if (!empty($_POST['password'])) {
        wp_set_password($_POST['password'], $user_id);
    }

    update_user_meta($user_id, 'email_verified', 0);
    unluckytech_send_verification_code($user_id);
}
add_action('user_register', 'unluckytech_save_password_and_send_verification');

// Send the verification code
function unluckytech_send_verification_code($user_id) {
    $user = get_user_by('ID', $user_id);
    $verification_code = wp_generate_password(20, false);
    update_user_meta($user_id, 'email_verification_code', $verification_code);

    $verification_link = add_query_arg(array(
        'verify_email' => 'true',
        'code' => $verification_code,
        'user_id' => $user_id
    ), site_url());

    $to = $user->user_email;
    $subject = "Verify your account on UnluckyTech";
    $message = "Click the following link to verify your email address: " . $verification_link;

    wp_mail($to, $subject, $message);
}

// Handle email verification upon user click
function unluckytech_verify_email() {
    if (isset($_GET['verify_email']) && $_GET['verify_email'] === 'true') {
        $user_id = intval($_GET['user_id']);
        $verification_code = sanitize_text_field($_GET['code']);
        $stored_code = get_user_meta($user_id, 'email_verification_code', true);

        if ($verification_code === $stored_code) {
            update_user_meta($user_id, 'email_verified', 1);
            delete_user_meta($user_id, 'email_verification_code');
            wp_redirect(site_url('/?email_verified=true'));
            exit;
        } else {
            wp_redirect(site_url('/?email_verified=false'));
            exit;
        }
    }
}
add_action('init', 'unluckytech_verify_email');

// Show success or error message on the login page
function unluckytech_show_verification_message() {
    if (isset($_GET['email_verified']) && $_GET['email_verified'] === 'true') {
        echo '<div class="verification-success">Your email has been successfully verified!</div>';
    } elseif (isset($_GET['email_verified']) && $_GET['email_verified'] === 'false') {
        echo '<div class="verification-failure">The email verification failed. Please try again.</div>';
    }
}
add_action('login_message', 'unluckytech_show_verification_message');

// Disable the default new user notification email to users
function disable_new_user_notification_email() {
    remove_action('register_new_user', 'wp_send_new_user_notifications');
}
add_action('init', 'disable_new_user_notification_email');