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
include_once plugin_dir_path( __FILE__ ) . 'includes/customer.php';
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

// Register the settings.
function znuny_register_settings() {
    // Credentials settings.
    register_setting('znuny-credentials-group', 'znuny_api_domain');
    register_setting('znuny-credentials-group', 'znuny_user_login');
    register_setting('znuny-credentials-group', 'znuny_password');
    
    // Tickets settings.
    register_setting('znuny-tickets-group', 'znuny_customer_user');
}

// Admin page content.
function znuny_settings_page() {
    // Check if a tab is set; if not, set 'credentials' as default.
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'credentials';
    ?>
    <div class="wrap">
        <h1>Znuny Integration Settings</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=znuny-settings&tab=credentials" class="nav-tab <?php echo $active_tab == 'credentials' ? 'nav-tab-active' : ''; ?>">Credentials</a>
            <a href="?page=znuny-settings&tab=tickets" class="nav-tab <?php echo $active_tab == 'tickets' ? 'nav-tab-active' : ''; ?>">Tickets</a>
        </h2>

        <?php
        // Credentials tab content
        if ($active_tab == 'credentials') {
            ?>
            <form method="post" action="options.php">
                <?php
                // Output security fields for the 'credentials' settings group.
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
                        <td><input type="password" name="znuny_password" value="<?php echo esc_attr(get_option('znuny_password')); ?>" /></td>
                    </tr>
                </table>
                <button id="znuny-test-api" class="button button-primary">Test API</button>
                <p id="znuny-test-result"></p>
                <?php submit_button('Save Changes'); ?>
            </form>
            <?php
        }

        // Tickets tab content
        if ($active_tab == 'tickets') {
            ?>
            <form method="post" action="options.php">
                <?php
                // Output security fields for the 'tickets' settings group.
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
    if ( $hook === 'toplevel_page_znuny-settings' ) {
        wp_enqueue_script( 'znuny-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), '1.0', true );
        wp_localize_script( 'znuny-script', 'znuny_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}
add_action( 'admin_enqueue_scripts', 'znuny_enqueue_admin_scripts' );

// AJAX handler for API testing.
function znuny_test_api() {
    $domain = sanitize_text_field($_POST['api_url']);

    // Ensure the domain starts with "http://" or "https://".
    if (!preg_match('/^https?:\/\//', $domain)) {
        $domain = 'https://' . $domain; // or use 'http://' depending on your preference.
    }

    // Append the Znuny API path to the domain.
    $api_url = rtrim($domain, '/') . '/znuny/nph-genericinterface.pl/Webservice/unluckytech';

    // Assuming znuny_api_domain is meant to be the sanitized domain.
    $znuny_api_domain = $domain; // Define the znuny_api_domain variable.

    $user_login = sanitize_text_field($_POST['user_login']);
    $password = sanitize_text_field($_POST['password']);

    // Debugging information.
    $debug_info = array(
        'API URL'         => $api_url,
        'Domain'          => $domain,         // Include domain in debug info
        'Znuny API Domain'=> $znuny_api_domain, // Include znuny_api_domain in debug info
    );

    // Attempt to authenticate using the Znuny API.
    $response = wp_remote_post($api_url . '/Session', array(
        'body' => json_encode(array(
            'UserLogin' => $user_login,
            'Password'  => $password,
        )),
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
    ));

    // Check for errors.
    if (is_wp_error($response)) {
        // Output debug info along with the error message.
        wp_send_json_error(array_merge($debug_info, array('Error' => $response->get_error_message())));
    } else {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Log the full response for further debugging.
        $debug_info['Response'] = $body; // Add full response to debug info.

        // If SessionID is not set, output debug info and error.
        if (!isset($data['SessionID'])) {
            wp_send_json_error(array_merge($debug_info, array('Response' => $data)));
        } else {
            // Success. Include debug info in success response without sensitive info.
            wp_send_json_success($debug_info); // Return debug info on success
        }
    }
}
add_action('wp_ajax_znuny_test_api', 'znuny_test_api');

// AJAX handler for creating a test ticket.
function znuny_create_test_ticket() {
    // Retrieve the domain from the settings
    $domain = get_option('znuny_api_domain');

    // Check if ticket data is provided
    if (!isset($_POST['ticket_data']) || empty($_POST['ticket_data'])) {
        wp_send_json_error('Ticket data is missing');
    }

    // Retrieve ticket data from the request
    $ticket_data = $_POST['ticket_data'];

    // Use the stored session ID directly from the variable
    global $stored_session_id; // Ensure you access the global variable
    if (!isset($stored_session_id) || empty($stored_session_id)) {
        wp_send_json_error('No valid session ID found. Current Session ID: ' . (isset($stored_session_id) ? $stored_session_id : 'Not set'));
    }

    // Append the Znuny API path to the domain.
    $api_url = rtrim($domain, '/') . '/znuny/nph-genericinterface.pl/Webservice/unluckytech/Ticket';

    // Set up the API request body (with the ticket data)
    $body = array(
        'SessionID' => sanitize_text_field($stored_session_id), // Use stored session ID
        'Ticket' => array(
            'Title' => sanitize_text_field($ticket_data['Ticket']['Title']),
            'Queue' => sanitize_text_field($ticket_data['Ticket']['Queue']),
            'StateID' => intval($ticket_data['Ticket']['StateID']),
            'PriorityID' => intval($ticket_data['Ticket']['PriorityID']),
            'CustomerUser' => sanitize_text_field($ticket_data['Ticket']['CustomerUser']),
        ),
        'Article' => array(
            'CommunicationChannel' => sanitize_text_field($ticket_data['Article']['CommunicationChannel']),
            'Subject' => sanitize_text_field($ticket_data['Article']['Subject']),
            'Body' => sanitize_textarea_field($ticket_data['Article']['Body']),
            'ContentType' => sanitize_text_field($ticket_data['Article']['ContentType']),
            'Charset' => sanitize_text_field($ticket_data['Article']['Charset']),
            'MimeType' => sanitize_text_field($ticket_data['Article']['MimeType']),
        )
    );

    // Send request to Znuny API using wp_remote_post()
    $response = wp_remote_post($api_url, array(
        'method' => 'POST',
        'body' => json_encode($body),
        'headers' => array(
            'Content-Type' => 'application/json'
        )
    ));

    // Check for errors in the API request
    if (is_wp_error($response)) {
        wp_send_json_error(array(
            'message' => 'API request failed: ' . $response->get_error_message(),
            'api_url' => $api_url // Include the api_url for debugging
        ));
    }

    // Parse the API response
    $response_body = wp_remote_retrieve_body($response);
    $response_data = json_decode($response_body, true);

    // Check for errors in the response data
    if (isset($response_data['Error'])) {
        wp_send_json_error(array(
            'message' => 'Error from API: ' . print_r($response_data['Error'], true), // Print array as string
            'response' => $response_data // Include full response for debugging
        ));
    }

    // Assuming the response is successful
    wp_send_json_success(array('ticket_number' => $response_data['Ticket']['TicketID']));
}
add_action('wp_ajax_znuny_create_test_ticket', 'znuny_create_test_ticket');