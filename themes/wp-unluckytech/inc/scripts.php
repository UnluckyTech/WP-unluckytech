<?php

function wp_unluckytech_scripts() {
    // Global CSS (loaded on every page)
    wp_enqueue_style('wp-unluckytech-style', get_stylesheet_uri());
    wp_enqueue_style('wp-unluckytech-global', get_template_directory_uri() . '/assets/css/global.css');
    wp_enqueue_style('wp-unluckytech-nav', get_template_directory_uri() . '/assets/css/nav.css');
    wp_enqueue_style('wp-unluckytech-footer', get_template_directory_uri() . '/assets/css/footer.css');
    wp_enqueue_style('wp-unluckytech-blog', get_template_directory_uri() . '/assets/css/extras/blog.css');
    wp_enqueue_style('wp-unluckytech-single', get_template_directory_uri() . '/assets/css/extras/single.css');

    // Global JS (loaded on every page)
    wp_enqueue_script('wp-unluckytech-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), null, true);
    wp_enqueue_script('particle-animation-js', get_template_directory_uri() . '/assets/js/particle-animation.js', array('jquery'), null, true);

    // Localize script for AJAX
    wp_localize_script('wp-unluckytech-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

    // Page-specific CSS/JS (conditionally loaded)
    if (is_front_page() || is_home()) {
        wp_enqueue_style('wp-unluckytech-home-welcome', get_template_directory_uri() . '/assets/css/home/welcome.css');
        wp_enqueue_style('wp-unluckytech-home-posts', get_template_directory_uri() . '/assets/css/home/posts.css');
        wp_enqueue_style('wp-unluckytech-home-serv-part', get_template_directory_uri() . '/assets/css/home/serv-part.css');
        wp_enqueue_script('slideshow-js', get_template_directory_uri() . '/assets/js/modules/slideshow.js', array(), null, true);
    }

    if (is_page('about')) {
        wp_enqueue_style('wp-unluckytech-about', get_template_directory_uri() . '/assets/css/about/about.css');
        wp_enqueue_script('about-js', get_template_directory_uri() . '/assets/js/modules/about.js', array(), null, true);
    }

    if (is_page('certifications')) {
        wp_enqueue_style('wp-unluckytech-certificate', get_template_directory_uri() . '/assets/css/about/certificate.css');
    }

    if (is_page('experience')) {
        wp_enqueue_style('wp-unluckytech-experience', get_template_directory_uri() . '/assets/css/about/experience.css');
    }

    if (is_page('contact')) {
        wp_enqueue_style('wp-unluckytech-contact', get_template_directory_uri() . '/assets/css/about/contact.css');
    }

    if (is_page('education')) {
        wp_enqueue_style('wp-unluckytech-edu', get_template_directory_uri() . '/assets/css/about/education.css');
        wp_enqueue_script('education-js', get_template_directory_uri() . '/assets/js/modules/education.js', array(), null, true);
    }

    if (is_page('account')) {
        wp_enqueue_style('wp-unluckytech-acc', get_template_directory_uri() . '/assets/css/account/account.css');
        wp_enqueue_script('education-js', get_template_directory_uri() . '/assets/js/modules/education.js', array(), null, true);
    }

    if (is_page('docs')) {
        wp_enqueue_style('wp-unluckytech-videos', get_template_directory_uri() . '/assets/css/docs/videos.css');
        wp_enqueue_style('wp-unluckytech-latest', get_template_directory_uri() . '/assets/css/docs/latest.css');
        wp_enqueue_style('wp-unluckytech-categories', get_template_directory_uri() . '/assets/css/docs/categories.css');
        wp_enqueue_script('latest-js', get_template_directory_uri() . '/assets/js/modules/latest.js', array(), null, true);
        wp_enqueue_script('videos-js', get_template_directory_uri() . '/assets/js/modules/videos.js', array(), null, true);
    }

    if (is_404()) {
        wp_enqueue_style('wp-unluckytech-404', get_template_directory_uri() . '/assets/css/extras/404.css');
        wp_enqueue_script('404-js', get_template_directory_uri() . '/assets/js/modules/404.js', array(), null, true);
    }

    if (is_page('account')) {
        wp_enqueue_style('wp-unluckytech-account', get_template_directory_uri() . '/assets/css/account/account.css');
    }

    // Shared CSS/JS for multiple pages (conditionally loaded)
    $shared_service = array('services','it-support', 'web-development', 'system-configuration', 'server-management', 'technical-consultation', 'custom-pc');
    if (is_page($shared_service)) {
        wp_enqueue_style('wp-unluckytech-service-services', get_template_directory_uri() . '/assets/css/services/services.css');
        wp_enqueue_script('faq-js', get_template_directory_uri() . '/assets/js/modules/faq.js', array(), null, true);
    }

}
add_action('wp_enqueue_scripts', 'wp_unluckytech_scripts');