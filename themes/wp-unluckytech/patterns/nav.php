<?php
/**
 * Title: Navigation
 * Slug: unluckytech/nav
 * Categories: text, featured
 * Description: This would be used for navigation of the website.
 */
?>

<?php
wp_nav_menu(array(
    'theme_location' => 'header-menu',
    'menu_class'     => 'header-menu-class',
));
?>