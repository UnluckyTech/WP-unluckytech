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

        // Enqueue editor styles (load Montserrat in the block editor so it
        // matches the front end).
        add_editor_style( array(
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap',
            'style.css',
        ) );

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

// Per-page SEO meta: description + basic Open Graph tags.
// Skips automatically if a dedicated SEO plugin (Yoast / Rank Math) is active.
function unluckytech_seo_meta() {
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }

    $default = get_bloginfo('description');
    if (trim($default) === '') {
        $default = 'UnluckyTech — IT services, web development, server management, and tech guides. Custom solutions and support for your projects.';
    }
    $desc = $default;
    $title   = wp_get_document_title();
    $url     = home_url(add_query_arg(null, null));
    $image   = get_theme_file_uri('assets/images/BizTitleOfficial.webp');

    if (is_singular()) {
        $post = get_queried_object();
        if (!empty($post->post_excerpt)) {
            $desc = $post->post_excerpt;
        } elseif (!empty($post->post_content)) {
            $desc = wp_trim_words(wp_strip_all_tags($post->post_content), 30, '…');
        }
        if (has_post_thumbnail($post)) {
            $image = get_the_post_thumbnail_url($post, 'large');
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $term_desc = term_description();
        if ($term_desc) {
            $desc = wp_trim_words(wp_strip_all_tags($term_desc), 30, '…');
        }
    } elseif (is_search()) {
        $desc = 'Search results for "' . get_search_query() . '" on ' . get_bloginfo('name') . '.';
    }

    $desc = trim($desc);
    if ($desc === '') {
        $desc = $default;
    }

    echo "\n";
    printf('<meta name="description" content="%s" />' . "\n", esc_attr($desc));
    printf('<meta property="og:title" content="%s" />' . "\n", esc_attr($title));
    printf('<meta property="og:description" content="%s" />' . "\n", esc_attr($desc));
    printf('<meta property="og:type" content="%s" />' . "\n", is_singular('post') ? 'article' : 'website');
    printf('<meta property="og:url" content="%s" />' . "\n", esc_url($url));
    printf('<meta property="og:site_name" content="%s" />' . "\n", esc_attr(get_bloginfo('name')));
    printf('<meta property="og:image" content="%s" />' . "\n", esc_url($image));
    printf('<meta name="twitter:card" content="%s" />' . "\n", 'summary_large_image');
}
add_action('wp_head', 'unluckytech_seo_meta', 1);

// Preconnect to Google Fonts hosts for faster Montserrat loading
function unluckytech_resource_hints($hints, $relation_type) {
    if ('preconnect' === $relation_type) {
        $hints[] = 'https://fonts.googleapis.com';
        $hints[] = ['href' => 'https://fonts.gstatic.com', 'crossorigin'];
    }
    return $hints;
}
add_filter('wp_resource_hints', 'unluckytech_resource_hints', 10, 2);

// Enqueue Font Awesome
function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '6.7.2', 'all');
    add_filter('style_loader_tag', function($html, $handle) {
        if ($handle === 'font-awesome') {
            $html = str_replace(
                "rel='stylesheet'",
                "rel='stylesheet' integrity='sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==' crossorigin='anonymous' referrerpolicy='no-referrer'",
                $html
            );
        }
        return $html;
    }, 10, 2);
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');

add_filter('login_headerurl',  fn() => home_url());
add_filter('login_headertext', fn() => get_bloginfo('name'));

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
