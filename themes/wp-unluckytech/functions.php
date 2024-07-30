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

        // Add support for post thumbnails
        add_theme_support( 'post-thumbnails' );

        // Add support for title tag
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
    wp_enqueue_style('wp-unluckytech-style', get_stylesheet_uri());
    wp_enqueue_style('wp-unluckytech-main', get_template_directory_uri() . '/assets/css/main.css', array('wp-unluckytech-style'));
    wp_enqueue_style('wp-unluckytech-home-posts', get_template_directory_uri() . '/assets/css/home/posts.css', array('wp-unluckytech-main'));
    wp_enqueue_style('wp-unluckytech-home-services', get_template_directory_uri() . '/assets/css/home/services.css', array('wp-unluckytech-main'));
    wp_enqueue_style('wp-unluckytech-home-welcome', get_template_directory_uri() . '/assets/css/home/welcome.css', array('wp-unluckytech-main'));
    wp_enqueue_style('wp-unluckytech-footer', get_template_directory_uri() . '/assets/css/footer.css', array('wp-unluckytech-main'));
    wp_enqueue_style('wp-unluckytech-toggle', get_template_directory_uri() . '/assets/css/toggle.css', array('wp-unluckytech-main'));
    wp_enqueue_style('wp-unluckytech-about', get_template_directory_uri() . '/assets/css/extras/about.css', array('wp-unluckytech-main'));
    wp_enqueue_style('wp-unluckytech-post', get_template_directory_uri() . '/assets/css/extras/single.css', array('wp-unluckytech-main'));
    wp_enqueue_style('wp-unluckytech-blog', get_template_directory_uri() . '/assets/css/extras/blog.css', array('wp-unluckytech-main'));
    wp_enqueue_style('wp-unluckytech-search', get_template_directory_uri() . '/assets/css/extras/search.css', array('wp-unluckytech-main'));



    wp_enqueue_script('wp-unluckytech-script', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'wp_unluckytech_scripts');

// Enqueue Particle Animation JavaScript and CSS
function enqueue_particle_animation_assets() {
    wp_enqueue_style('particle-animation-css', get_template_directory_uri() . '/assets/css/particle-animation.css');
    wp_enqueue_script('particle-animation-js', get_template_directory_uri() . '/assets/js/particle-animation.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_particle_animation_assets');

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

function enqueue_slideshow_script() {
    wp_enqueue_script('slideshow-js', get_template_directory_uri() . '/assets/js/slideshow.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_slideshow_script');

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

function enqueue_google_fonts() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&display=swap', false);
}
add_action('wp_enqueue_scripts', 'enqueue_google_fonts');

// Add AJAX action for categories
add_action('wp_ajax_get_categories', 'get_categories_callback');
add_action('wp_ajax_nopriv_get_categories', 'get_categories_callback');

// Add AJAX action for tags
add_action('wp_ajax_get_tags', 'get_tags_callback');
add_action('wp_ajax_nopriv_get_tags', 'get_tags_callback');

function get_categories_callback() {
    $categories = get_categories();
    $response = array();
    foreach ($categories as $category) {
        $response[] = array(
            'id' => $category->term_id,
            'name' => $category->name
        );
    }
    wp_send_json($response);
}

function get_tags_callback() {
    $tags = get_tags();
    $response = array();
    foreach ($tags as $tag) {
        $response[] = array(
            'id' => $tag->term_id,
            'name' => $tag->name
        );
    }
    wp_send_json($response);
}
