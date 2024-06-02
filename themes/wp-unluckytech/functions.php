<?php

if ( ! function_exists( 'unlucky_theme_support' ) ) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * @since My theme name 1.0
     *
     * @return void
     */

endif;
add_action( 'after_setup_theme', 'unlucky_theme_support' );

/*-----------------------------------------------------------
Enqueue Styles
------------------------------------------------------------*/

if ( ! function_exists( 'unlucky_styles' ) ) :

    function unlucky_styles() {
        // Register Stylesheet
        wp_enqueue_style('unlucky-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
        wp_enqueue_style('unlucky-style-blocks', get_template_directory_uri() . '/assets/css/blocks.css');

        // Enqueue Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

    }

endif;

add_action( 'wp_enqueue_scripts', 'unlucky_styles' );

/*-----------------------------------------------------------
Customising The Excerpt Length
------------------------------------------------------------*/
function custom_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'custom_excerpt_length');

// Enqueue Particle Animation JavaScript and CSS
function enqueue_particle_animation_assets() {
    wp_enqueue_style('particle-animation-css', get_template_directory_uri() . '/assets/css/particle-animation.css');
    wp_enqueue_script('particle-animation-js', get_template_directory_uri() . '/assets/js/particle-animation.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_particle_animation_assets');