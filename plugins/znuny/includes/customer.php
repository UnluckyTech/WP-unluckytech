<?php

class Znuny_Customer {

    private $api;

    public function __construct() {
        $this->api = new Znuny_API();
    }

    // Function to create a new customer using the API.
    public function create_new_customer( $email, $name ) {
        $customer_data = array(
            'UserLogin' => $email,
            'Email'     => $email,
            'FirstName' => $name['first_name'],
            'LastName'  => $name['last_name'],
            'Password'  => wp_generate_password(),
            'CustomerID'=> '12345',  // Optionally set your CustomerID logic.
        );
        
        return $this->api->create_customer( $customer_data );
    }
}
