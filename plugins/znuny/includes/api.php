<?php

class Znuny_API {

    private $api_url;
    private $session_id;

    public function __construct() {
        // Cache the domain and construct the API URL once.
        $domain = get_option('znuny_api_domain');
        $api_path = '/znuny/nph-genericinterface.pl/Webservice/unluckytech';
        $this->api_url = rtrim($domain, '/') . $api_path;
        $this->session_id = '';
    }

    public function authenticate() {
        static $cached_user_login = null;
        static $cached_password = null;
    
        // Cache the user login and password to avoid multiple get_option() calls.
        if (is_null($cached_user_login)) {
            $cached_user_login = get_option('znuny_user_login');
        }
        if (is_null($cached_password)) {
            $cached_password = get_option('znuny_password');
        }
    
        $response = wp_remote_post($this->api_url . '/Session', array(
            'body' => json_encode(array(
                'UserLogin' => $cached_user_login,
                'Password'  => $cached_password,
            )),
            'headers' => array('Content-Type' => 'application/json'),
        ));

        // Handle response.
        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        if ( isset( $data['SessionID'] ) ) {
            $this->session_id = $data['SessionID'];
            return $this->session_id;
        }

        return false;
    }
}

// AJAX handler for API testing.
function znuny_test_api() {
    // Get the domain and user login from the POST request.
    $domain = sanitize_text_field($_POST['api_url']);
    $user_login = sanitize_text_field($_POST['user_login']);
    $password = sanitize_text_field($_POST['password']);

    // Ensure the domain starts with "http://" or "https://".
    if (!preg_match('/^https?:\/\//', $domain)) {
        $domain = 'https://' . $domain; // Ensure HTTPS.
    }

    // Append the Znuny API path to the domain.
    $api_url = rtrim($domain, '/') . '/znuny/nph-genericinterface.pl/Webservice/unluckytech';

    // If the password is not provided in the form, get the stored password from the database.
    if (empty($password)) {
        $password = get_option('znuny_password'); // Retrieve the stored password.
    }

    // Debugging information (for easier debugging of the request).
    $debug_info = array(
        'API URL'         => $api_url,
        'Domain'          => $domain,
        'UserLogin'       => $user_login,
    );

    // Attempt to authenticate using the Znuny API.
    $response = wp_remote_post($api_url . '/Session', array(
        'body' => json_encode(array(
            'UserLogin' => $user_login,
            'Password'  => $password, // Use the provided or stored password.
        )),
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
    ));

    // Check for errors in the API request.
    if (is_wp_error($response)) {
        // Output debug info along with the error message.
        wp_send_json_error(array_merge($debug_info, array('Error' => $response->get_error_message())));
    } else {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Add full response to debug info for troubleshooting.
        $debug_info['Response'] = $body;

        // If the SessionID is not set, the API call failed.
        if (!isset($data['SessionID'])) {
            wp_send_json_error(array_merge($debug_info, array('Response' => $data)));
        } else {
            // Successful authentication; return the debug info.
            wp_send_json_success($debug_info); // Return debug info on success without sensitive info.
        }
    }
}
add_action('wp_ajax_znuny_test_api', 'znuny_test_api');