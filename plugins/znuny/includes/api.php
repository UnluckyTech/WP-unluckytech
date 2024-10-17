<?php

class Znuny_API {

    private $api_url;
    private $user_login;
    private $password;

    public function __construct() {
        // Cache the domain and construct the API URL once.
        $domain = get_option('znuny_api_domain');
        $api_path = '/znuny/nph-genericinterface.pl/Webservice/unluckytech';
        $this->api_url = rtrim($domain, '/') . $api_path;

        // Directly retrieve user credentials
        $this->user_login = get_option('znuny_user_login');
        $this->password = get_option('znuny_password');
    }

    // Example method to show how to use the credentials directly.
    public function make_request($endpoint, $data = []) {
        $response = wp_remote_post($this->api_url . $endpoint, array(
            'body' => json_encode($data),
            'headers' => array('Content-Type' => 'application/json'),
            'timeout' => 5, // Timeout set to 5 seconds
        ));

        // Handle response
        if (is_wp_error($response)) {
            error_log("Request error: " . $response->get_error_message());
            return false;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    // Additional methods for your API requests can be added here.
}

