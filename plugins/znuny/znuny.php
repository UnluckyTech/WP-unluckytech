<?php
/*
Plugin Name: Znuny Integration
Description: Integrates Znuny (OTRS) with WordPress.
Version: 1.0
Author: UnluckyTech
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Include necessary files.
include_once plugin_dir_path( __FILE__ ) . 'includes/api.php';

// Initialize the plugin and settings.
function znuny_integration_init() {
    // Register the admin menu.
    add_action( 'admin_menu', 'znuny_create_admin_menu' );
    // Register settings.
    add_action( 'admin_init', 'znuny_register_settings' );
}
add_action( 'init', 'znuny_integration_init' );

// Create admin menu under "Settings".
function znuny_create_admin_menu() {
    add_menu_page(
        'Znuny Integration Settings',    // Page title.
        'Znuny Integration',             // Menu title.
        'manage_options',                // Capability.
        'znuny-settings',                // Menu slug.
        'znuny_settings_page',           // Callback function.
        'dashicons-admin-generic',       // Icon.
        110                              // Position.
    );
}

// Register the settings, do not show the password value.
function znuny_register_settings() {
    register_setting('znuny-credentials-group', 'znuny_api_domain');
    register_setting('znuny-credentials-group', 'znuny_user_login');
    register_setting('znuny-credentials-group', 'znuny_password', array(
        'sanitize_callback' => 'znuny_sanitize_password'
    ));
}

// Sanitize password - only update if a new one is provided.
function znuny_sanitize_password($password) {
    // If the password field is empty, keep the existing password.
    if (empty($password)) {
        return get_option('znuny_password'); // Return the existing password if no new password is provided.
    } else {
        return $password; // Update with new password if entered.
    }
}

// Admin page content.
function znuny_settings_page() {
    // Show success message if settings have been saved.
    if (isset($_GET['settings-updated'])) {
        add_settings_error('znuny_messages', 'znuny_message', __('Settings saved.', 'znuny'), 'updated');
    }

    // Show error/success messages
    settings_errors('znuny_messages');
    ?>
    <div class="wrap">
        <h1>Znuny Integration Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('znuny-credentials-group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Znuny API Domain</th>
                    <td><input type="text" name="znuny_api_domain" value="<?php echo esc_attr(get_option('znuny_api_domain')); ?>" placeholder="https://support.unluckytech.com" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">User Login</th>
                    <td><input type="text" name="znuny_user_login" value="<?php echo esc_attr(get_option('znuny_user_login')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Password</th>
                    <td><input type="password" name="znuny_password" value="" placeholder="Enter new password (leave blank to keep current)" /></td>
                </tr>
            </table>
            <?php submit_button('Save Changes'); ?>
        </form>
    </div>
    <?php
}
?>
