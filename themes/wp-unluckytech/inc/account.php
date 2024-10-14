<?php

function unluckytech_save_profile() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        // Sanitize and update the user data
        $userdata = array(
            'ID'           => $current_user->ID,
            'user_email'   => sanitize_email($_POST['email']),
            'first_name'   => sanitize_text_field($_POST['first_name']),
            'last_name'    => sanitize_text_field($_POST['last_name']),
        );

        // Update password if it's set
        if (!empty($_POST['password'])) {
            $userdata['user_pass'] = $_POST['password'];
        }

        wp_update_user($userdata);

        // Redirect back to the account page with a query parameter to show the "Profile Saved" popup
        wp_redirect(add_query_arg('profile_saved', 'true', site_url('/account/')));
        exit;
    }
}
add_action('admin_post_save_profile', 'unluckytech_save_profile');