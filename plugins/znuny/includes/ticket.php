<?php
// ticket.php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Function to create a test ticket
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
            'Type'         => 'Unclassified', // Set the Type based on the selected service
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
