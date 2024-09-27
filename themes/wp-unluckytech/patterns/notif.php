<?php
/**
 * Title: Notifications
 * Slug: unluckytech/notification
 * Categories: text, featured
 * Description: This would be used for notification of the website.
 */

// Check if the user is logged in and can manage their notification settings
if (is_user_logged_in()) {
    $user_id = get_current_user_id();

    // Retrieve the user's notification preference
    $notifications_enabled = get_user_meta($user_id, 'receive_notifications', true);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_nonce']) && wp_verify_nonce($_POST['notification_nonce'], 'update_notification_settings')) {
        $notifications_enabled = isset($_POST['notification_toggle']) && $_POST['notification_toggle'] === 'on' ? 1 : 0;
        update_user_meta($user_id, 'receive_notifications', $notifications_enabled);

        // Show a confirmation message
        echo '<div class="notification-message">Your settings have been updated.</div>';
    }
?>

<div class="notification-section">
    <h2 class="acc-title">Notifications</h2>
    <form method="POST">
        <?php wp_nonce_field('update_notification_settings', 'notification_nonce'); ?>
        <label for="notification_toggle">
            <input type="checkbox" name="notification_toggle" id="notification_toggle" <?php echo $notifications_enabled ? 'checked' : ''; ?> />
            Receive notifications for new posts
        </label>
        <br/>
        <div class="about-answer">
                <p>You will only receive emails in regards to changes with your account. If you would like to receive emails for latest projects you may enable such but you will not receive any other emails.</p>
            </div>
        <button type="submit">Save Settings</button>
    </form>
</div>

<?php
} else {
    echo '<p>Please log in to manage your notification settings.</p>';
}
?>
