<?php



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