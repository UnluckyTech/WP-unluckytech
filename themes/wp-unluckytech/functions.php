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
        'wp-unluckytech-home-posts' => get_template_directory_uri() . '/assets/css/home/posts.css',
        'wp-unluckytech-home-services' => get_template_directory_uri() . '/assets/css/home/services.css',
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

// Customize Admin Bar Menu
function custom_admin_bar_menu( $wp_admin_bar ) {
    // Remove the 'Site Name' link (typically the top-level site link)
    $wp_admin_bar->remove_node('site-editor');
    $wp_admin_bar->remove_node('edit');
    
    // Add the 'Customize' link with an icon
    $wp_admin_bar->add_node( array(
        'id'    => 'customize',
        'title' => '<span class="ab-icon"></span><span class="ab-label">Customize</span>',
        'href'  => admin_url('customize.php'),
        'meta'  => array(
            'class' => 'customize-icon', // Add a custom class for styling
        ),
    ) );
}
add_action( 'admin_bar_menu', 'custom_admin_bar_menu', 999 );

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

/**
 * Email Verification Functions
 */

// Function to send email verification code
function send_verification_code() {
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in.');
        return;
    }

    $current_user = wp_get_current_user();
    $email = $current_user->user_email;

    // Generate a random 6-digit verification code
    $verification_code = wp_generate_password(6, false, false);

    // Save the verification code as user meta
    update_user_meta($current_user->ID, 'email_verification_code', $verification_code);

    $subject = 'Your Email Verification Code';
    $message = 'Your email verification code is: ' . $verification_code;
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    // Check if WP Mail SMTP plugin is active and send the email
    if (is_plugin_active('wp-mail-smtp/wp_mail_smtp.php') && wp_mail($email, $subject, $message, $headers)) {
        wp_send_json_success('Verification code sent! Check your email.');
    } else {
        wp_send_json_error('Failed to send verification code.');
    }
}

// Function to verify the email code
function verify_email_code() {
    if (!is_user_logged_in()) {
        wp_redirect(home_url());
        exit;
    }

    $current_user = wp_get_current_user();
    $input_code = isset($_POST['verification_code']) ? sanitize_text_field($_POST['verification_code']) : '';

    // Get the stored verification code
    $stored_code = get_user_meta($current_user->ID, 'email_verification_code', true);

    // Check if the input code matches the stored code
    if ($input_code === $stored_code) {
        delete_user_meta($current_user->ID, 'email_verification_code');
        wp_redirect(add_query_arg('verification_success', 'true', site_url('/account/')));
    } else {
        wp_redirect(add_query_arg('verification_error', 'true', site_url('/account/')));
    }
    exit;
}

// Add actions for the above functions
add_action('wp_ajax_send_verification_code', 'send_verification_code');
add_action('wp_ajax_nopriv_send_verification_code', 'send_verification_code');
add_action('admin_post_verify_email_code', 'verify_email_code');

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