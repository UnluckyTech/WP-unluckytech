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
include_once plugin_dir_path( __FILE__ ) . 'includes/ticket.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/auth.php';

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
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'credentials';
    ?>
    <div class="wrap">
        <h1>Znuny Integration Settings</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=znuny-settings&tab=credentials" class="nav-tab <?php echo $active_tab == 'credentials' ? 'nav-tab-active' : ''; ?>">Credentials</a>
            <a href="?page=znuny-settings&tab=tickets" class="nav-tab <?php echo $active_tab == 'tickets' ? 'nav-tab-active' : ''; ?>">Tickets</a>
        </h2>

        <?php
        if ($active_tab == 'credentials') {
            ?>
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
                <button id="znuny-test-api" class="button button-primary">Test API</button>
                <p id="znuny-test-result"></p>
                <?php submit_button('Save Changes'); ?>
            </form>
            <?php
        }

        if ($active_tab == 'tickets') {
            ?>
            <form method="post" action="options.php">
                <?php
                settings_fields('znuny-tickets-group');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Customer User</th>
                        <td><input type="text" name="znuny_customer_user" value="<?php echo esc_attr(get_option('znuny_customer_user')); ?>" placeholder="Customer User" /></td>
                    </tr>
                </table>
                <button id="znuny-create-ticket" class="button button-primary">Create Test Ticket</button>
                <p id="znuny-ticket-result"></p>
                <?php submit_button('Save Changes'); ?>
            </form>
            <?php
        }
        ?>
    </div>
    <?php
}



// Enqueue custom script for API testing.
function znuny_enqueue_admin_scripts( $hook ) {
    // Only load script on the Znuny settings page
    if ( $hook === 'toplevel_page_znuny-settings' ) {
        wp_enqueue_script( 'znuny-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), '1.0', true );
        
        // Localize variables for use in the script
        wp_localize_script( 'znuny-script', 'znuny_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'user_login' => esc_js(get_option('znuny_user_login')), // Send user login from settings
            'password' => esc_js(get_option('znuny_password')) // Send password from settings
        ));
    }
}
add_action( 'admin_enqueue_scripts', 'znuny_enqueue_admin_scripts' );
