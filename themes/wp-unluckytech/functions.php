<?php

if ( ! function_exists( 'unlucky_theme_support' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * @since My Theme Name 1.0
     *
     * @return void
     */
    function unlucky_theme_support() {
        // Add support for block styles.
        add_theme_support( 'wp-block-styles' );

        // Enqueue editor styles.
        add_editor_style( 'style.css' );

        // Add support for post thumbnails.
        add_theme_support( 'post-thumbnails' );

        // Add support for title tag.
        add_theme_support( 'title-tag' );
    }
endif;
add_action( 'after_setup_theme', 'unlucky_theme_support' );

/*-----------------------------------------------------------
Customizing The Excerpt Length
------------------------------------------------------------*/
function custom_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'custom_excerpt_length');

// Enqueue Theme Styles and Scripts
function wp_unluckytech_scripts() {
    // Enqueue styles
    $styles = array(
        'wp-unluckytech-style' => get_stylesheet_uri(),
        'wp-unluckytech-nav' => get_template_directory_uri() . '/assets/css/nav.css',
        'wp-unluckytech-footer' => get_template_directory_uri() . '/assets/css/footer.css',
        'wp-unluckytech-home-posts' => get_template_directory_uri() . '/assets/css/home/posts.css',
        'wp-unluckytech-home-serv-part' => get_template_directory_uri() . '/assets/css/home/serv-part.css',
        'wp-unluckytech-home-services' => get_template_directory_uri() . '/assets/css/services.css',
        'wp-unluckytech-home-welcome' => get_template_directory_uri() . '/assets/css/home/welcome.css',
        'wp-unluckytech-post' => get_template_directory_uri() . '/assets/css/extras/single.css',
        'wp-unluckytech-blog' => get_template_directory_uri() . '/assets/css/extras/blog.css',
        'wp-unluckytech-search' => get_template_directory_uri() . '/assets/css/extras/search.css',
        'wp-unluckytech-videos' => get_template_directory_uri() . '/assets/css/docs/videos.css',
        'wp-unluckytech-latest' => get_template_directory_uri() . '/assets/css/docs/latest.css',
        'wp-unluckytech-categories' => get_template_directory_uri() . '/assets/css/docs/categories.css',
        'wp-unluckytech-about' => get_template_directory_uri() . '/assets/css/about/about.css',
        'wp-unluckytech-contact' => get_template_directory_uri() . '/assets/css/about/contact.css',
        'wp-unluckytech-cert' => get_template_directory_uri() . '/assets/css/about/certificate.css',
        'wp-unluckytech-exp' => get_template_directory_uri() . '/assets/css/about/experience.css',
        'wp-unluckytech-edu' => get_template_directory_uri() . '/assets/css/about/education.css',
        'wp-unluckytech-intro' => get_template_directory_uri() . '/assets/css/about/intro.css',
        'wp-unluckytech-404' => get_template_directory_uri() . '/assets/css/extras/404.css',
        'wp-unluckytech-account' => get_template_directory_uri() . '/assets/css/account/account.css',
    );

    foreach ($styles as $handle => $src) {
        wp_enqueue_style($handle, $src);
    }

    // Enqueue scripts
    $scripts = array(
        'wp-unluckytech-script' => get_template_directory_uri() . '/assets/js/custom.js',
        'slideshow-js' => get_template_directory_uri() . '/assets/js/slideshow.js',
    );

    foreach ($scripts as $handle => $src) {
        wp_enqueue_script($handle, $src, array('jquery'), null, true);
    }

    // Enqueue Particle Animation JavaScript and CSS
    wp_enqueue_style('particle-animation-css', get_template_directory_uri() . '/assets/css/particle-animation.css');
    wp_enqueue_script('particle-animation-js', get_template_directory_uri() . '/assets/js/particle-animation.js', array('jquery'), null, true);

    // Localize script to pass the AJAX URL
    wp_localize_script('wp-unluckytech-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'wp_unluckytech_scripts');

// Import Adobe Font CSS
function import_adobe_font_css() {
    echo '<style>@import url("https://use.typekit.net/imp2jke.css");</style>';
}
add_action('wp_head', 'import_adobe_font_css');

// Enqueue Font Awesome
function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');



// Register Navigation Menus
function register_my_menus() {
    register_nav_menus(
        array(
            'header-menu' => __( 'Header Menu' ),
            'extra-menu' => __( 'Extra Menu' )
        )
    );
}
add_action( 'init', 'register_my_menus' );

// Function to get categories
function get_categories_ajax() {
    $categories = get_categories();
    $result = array();

    foreach ($categories as $category) {
        $result[] = array(
            'id' => $category->slug, // Use the slug as value
            'name' => $category->name,
        );
    }

    wp_send_json($result);
}
add_action('wp_ajax_get_categories', 'get_categories_ajax');
add_action('wp_ajax_nopriv_get_categories', 'get_categories_ajax');

// Function to get tags
function get_tags_ajax() {
    $tags = get_tags();
    $result = array();

    foreach ($tags as $tag) {
        $result[] = array(
            'id' => $tag->slug, // Use the slug as value
            'name' => $tag->name,
        );
    }

    wp_send_json($result);
}
add_action('wp_ajax_get_tags', 'get_tags_ajax');
add_action('wp_ajax_nopriv_get_tags', 'get_tags_ajax');

function custom_login_logo() {
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/BizTitleLite.webp);
            padding-bottom: 30px;
            background-size: contain;
            width: 100%;
            height: 80px; /* Adjust height as needed */
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'custom_login_logo');

function unluckytech_save_profile() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        // Sanitize and update the user data
        $userdata = array(
            'ID'           => $current_user->ID,
            'user_email'   => sanitize_email($_POST['email']),
            'first_name'   => sanitize_text_field($_POST['first_name']),
            'last_name'    => sanitize_text_field($_POST['last_name']),
        );

        // Update password if it's set
        if (!empty($_POST['password'])) {
            $userdata['user_pass'] = $_POST['password'];
        }

        wp_update_user($userdata);

        // Redirect back to the account page with a query parameter to show the "Profile Saved" popup
        wp_redirect(add_query_arg('profile_saved', 'true', site_url('/account/')));
        exit;
    }
}
add_action('admin_post_save_profile', 'unluckytech_save_profile');

// Enqueue custom login styles and add particle animation HTML
function unluckytech_login_styles() {
    wp_enqueue_style('login-css', get_template_directory_uri() . '/assets/css/login.css');

    // Enqueue Particle Animation JavaScript
    wp_enqueue_script('particle-animation-js', get_template_directory_uri() . '/assets/js/particle-animation.js', array('jquery'), null, true);

    // Add particle animation HTML to the login page
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

// Redirect users to the homepage after login
function unluckytech_login_redirect($redirect_to, $request, $user) {
    return home_url();
}
add_filter('login_redirect', 'unluckytech_login_redirect', 10, 3);

// Disable admin bar for non-admin users
function disable_admin_bar_for_non_admins() {
    if (!current_user_can('administrator')) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('wp_loaded', 'disable_admin_bar_for_non_admins');

// Add password and confirm password fields to the registration form
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

// Validate password fields during registration
function unluckytech_validate_password_fields($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $errors->add('password_error', __('<strong>ERROR</strong>: You must include both password and confirm password.'));
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $errors->add('password_mismatch', __('<strong>ERROR</strong>: Passwords do not match.'));
    }
    return $errors;
}
add_filter('registration_errors', 'unluckytech_validate_password_fields', 10, 3);

// Save the password during user registration and send verification email
function unluckytech_save_password_and_send_verification($user_id) {
    if (!empty($_POST['password'])) {
        wp_set_password($_POST['password'], $user_id);
    }

    // Automatically mark the user as 'unverified'
    update_user_meta($user_id, 'email_verified', 0);

    // Generate and send the email verification code
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
