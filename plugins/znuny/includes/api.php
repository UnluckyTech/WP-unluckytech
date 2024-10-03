<?php

class Znuny_API {

    private $api_url;
    private $session_id;

    public function __construct() {
        // Fetch the base domain entered by the user.
        $domain = get_option( 'znuny_api_domain' );

        // Define the Znuny API path.
        $api_path = '/znuny/nph-genericinterface.pl/Webservice/unluckytech';

        // Construct the full API URL.
        $this->api_url = rtrim($domain, '/') . $api_path;

        $this->session_id = '';  // Session management logic.
    }

    public function authenticate() {
        $user_login = get_option( 'znuny_user_login' );
        $password = get_option( 'znuny_password' );

        $response = wp_remote_post( $this->api_url . '/Session', array(
            'body' => json_encode( array(
                'UserLogin' => $user_login,
                'Password'  => $password,
            ) ),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
        ) );

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