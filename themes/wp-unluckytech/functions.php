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

require get_template_directory() . '/inc/scripts.php';

add_action('wp_authenticate', 'include_login_file_before_login');

function include_login_file_before_login() {
    require_once get_template_directory() . '/inc/login.php';
}

// Include registration.php only on the wp-login.php page
function include_registration_script() {
    if ( is_login() ) {
        require get_template_directory() . '/inc/registration.php';
    }
}
add_action('init', 'include_registration_script');

function load_account_script() {
    if ( is_page('account') ) { // Replace 'account' with your page slug or ID
        require get_template_directory() . '/inc/account.php';
    }
}
add_action('wp', 'load_account_script');

// Enqueue Font Awesome
function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4', 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');

// Redirect users to the homepage after login
function unluckytech_login_redirect($redirect_to, $request, $user) {
    return home_url();
}
add_filter('login_redirect', 'unluckytech_login_redirect', 10, 3);