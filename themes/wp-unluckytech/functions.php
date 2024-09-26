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

// Add first and last name fields to the registration form
function unluckytech_registration_fields() {
    ?>
    <p>
        <label for="first_name"><?php _e('First Name') ?><br />
        <input type="text" name="first_name" id="first_name" class="input" value="<?php if (!empty($_POST['first_name'])) echo esc_attr($_POST['first_name']); ?>" size="25" /></label>
    </p>
    <p>
        <label for="last_name"><?php _e('Last Name') ?><br />
        <input type="text" name="last_name" id="last_name" class="input" value="<?php if (!empty($_POST['last_name'])) echo esc_attr($_POST['last_name']); ?>" size="25" /></label>
    </p>
    <?php
}
add_action('register_form', 'unluckytech_registration_fields');

// Validate the first and last name fields during registration
function unluckytech_validate_registration_fields($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST['first_name']) || empty($_POST['last_name'])) {
        $errors->add('first_last_name_error', __('<strong>ERROR</strong>: You must include a first and last name.'));
    }
    return $errors;
}
add_filter('registration_errors', 'unluckytech_validate_registration_fields', 10, 3);

// Save the first and last name fields during user registration
function unluckytech_save_registration_fields($user_id) {
    if (!empty($_POST['first_name'])) {
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (!empty($_POST['last_name'])) {
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
    }
    
    // Automatically mark the user as 'unverified'
    update_user_meta($user_id, 'email_verified', 0);

    // Generate and send the email verification code
    unluckytech_send_verification_code($user_id);
}
add_action('user_register', 'unluckytech_save_registration_fields');