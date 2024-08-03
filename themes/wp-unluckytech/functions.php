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
        'wp-unluckytech-toggle' => get_template_directory_uri() . '/assets/css/toggle.css',
        'wp-unluckytech-about' => get_template_directory_uri() . '/assets/css/extras/about.css',
        'wp-unluckytech-post' => get_template_directory_uri() . '/assets/css/extras/single.css',
        'wp-unluckytech-blog' => get_template_directory_uri() . '/assets/css/extras/blog.css',
        'wp-unluckytech-search' => get_template_directory_uri() . '/assets/css/extras/search.css',
        'wp-unluckytech-alternative' => get_template_directory_uri() . '/assets/css/docs/alternative.css',
        'wp-unluckytech-latest' => get_template_directory_uri() . '/assets/css/docs/latest.css',
        'wp-unluckytech-categories' => get_template_directory_uri() . '/assets/css/docs/categories.css',
    );

    foreach ($styles as $handle => $src) {
        wp_enqueue_style($handle, $src);
    }

    // Enqueue scripts
    $scripts = array(
        'wp-unluckytech-script' => get_template_directory_uri() . '/assets/js/custom.js',
        'slideshow-js' => get_template_directory_uri() . '/assets/js/slideshow.js',
        'slideshow-js' => get_template_directory_uri() . '/assets/js/docslides.js',
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
            'id' => $category->term_id,
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
            'id' => $tag->term_id,
            'name' => $tag->name,
        );
    }

    wp_send_json($result);
}
add_action('wp_ajax_get_tags', 'get_tags_ajax');
add_action('wp_ajax_nopriv_get_tags', 'get_tags_ajax');
