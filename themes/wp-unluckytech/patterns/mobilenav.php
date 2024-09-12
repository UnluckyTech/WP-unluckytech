<?php
/**
 * Title: Mobile Navigation
 * Slug: unluckytech/mobilenav
 * Categories: text, featured
 * Description: This would be used for navigation of the website.
 */
?>

<!-- Login/Signup or Account/Logout buttons -->
<div class="auth-buttons">
    <?php if (is_user_logged_in()) : ?>
        <button onclick="window.location.href='<?php echo esc_url(home_url('/account')); ?>'">Account</button>
        <button onclick="window.location.href='<?php echo esc_url(wp_logout_url(home_url())); ?>'">Log Out</button>
    <?php else : ?>
        <button onclick="window.location.href='<?php echo esc_url(home_url('/login')); ?>'">Log In</button>
        <button onclick="window.location.href='<?php echo wp_registration_url(); ?>'">Sign Up</button>
    <?php endif; ?>
</div>