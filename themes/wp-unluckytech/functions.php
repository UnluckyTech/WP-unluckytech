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

// Hook into the password reset action to reset failed login attempts using reset.php
add_action('after_password_reset', 'trigger_reset_script_on_password_reset', 10, 2);
function trigger_reset_script_on_password_reset($user, $new_pass) {
    // Include the reset.php file
    require_once get_template_directory() . '/inc/reset.php';

    // Call the function to reset failed login attempts
    reset_failed_login_attempts($user->ID);

    // Log the reset (for debugging purposes)
    error_log("Password reset and failed login attempts cleared for user ID {$user->ID}");
}

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

function modify_blog_query($query) {
    // Check if it's the main query and it's a blog query (is_home or is_category, for example)
    if (!is_admin() && $query->is_main_query() && (is_home() || is_category() || is_tag())) {
        
        // Set pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $query->set('paged', $paged);
        
        // Set posts per page
        $query->set('posts_per_page', 5);
        
        // Sorting based on URL query parameters
        if (isset($_GET['sort'])) {
            $sort = sanitize_text_field($_GET['sort']);
            switch ($sort) {
                case 'title_asc':
                    $query->set('orderby', 'title');
                    $query->set('order', 'ASC');
                    break;
                case 'title_desc':
                    $query->set('orderby', 'title');
                    $query->set('order', 'DESC');
                    break;
                case 'date_asc':
                    $query->set('orderby', 'date');
                    $query->set('order', 'ASC');
                    break;
                case 'date_desc':
                default:
                    $query->set('orderby', 'date');
                    $query->set('order', 'DESC');
                    break;
            }
        }

        // Filter by category if provided
        if (isset($_GET['category']) && $_GET['category'] != 'all') {
            $category = sanitize_text_field($_GET['category']);
            $query->set('category_name', $category);
        }

        // Filter by tag if provided
        if (isset($_GET['tag']) && $_GET['tag'] != 'all') {
            $tag = sanitize_text_field($_GET['tag']);
            $query->set('tag', $tag);
        }
    }
}
add_action('pre_get_posts', 'modify_blog_query');
