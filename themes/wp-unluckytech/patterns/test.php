<?php
/**
 * Title: Test
 * Slug: unluckytech/test
 * Categories: text, featured
 * Description: This is a test file to experiment.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Load necessary WordPress functions and Znuny integration plugin if needed.
require_once(ABSPATH . 'wp-load.php');
// Include the authentication file to access the session ID
include_once ABSPATH . 'wp-content/plugins/znuny/includes/auth.php'; // This should work based on your plugins directory.
include_once ABSPATH . 'wp-content/plugins/znuny/znuny.php'; // This should work based on your plugins directory.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ticket'])) {
    // Fetch the session ID.
    $domain = get_option('znuny_api_domain');
    $user_login = get_option('znuny_user_login');
    $password = get_option('znuny_password');

    // Log request details
    echo '<h3>Session Request Data:</h3>';
    echo 'API Domain: ' . esc_html($domain) . '<br>';
    echo 'User Login: ' . esc_html($user_login) . '<br>';

    // Make API call to authenticate and get Session ID.
    $session_response = wp_remote_post($domain . '/znuny/nph-genericinterface.pl/Webservice/unluckytech/Session', array(
        'body' => json_encode(array(
            'UserLogin' => $user_login,
            'Password'  => $password,
        )),
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
    ));

    if (is_wp_error($session_response)) {
        echo 'Error retrieving session ID: ' . esc_html($session_response->get_error_message());
        return;
    }

    // Log session response
    $session_status_code = wp_remote_retrieve_response_code($session_response);
    $session_body = wp_remote_retrieve_body($session_response);
    echo '<h3>Session Response Data:</h3>';
    echo 'Status Code: ' . esc_html($session_status_code) . '<br>';
    echo 'Response Body: ' . esc_html($session_body) . '<br>';

    $session_data = json_decode($session_body, true);

    if (!isset($session_data['SessionID'])) {
        echo 'Failed to retrieve Session ID.';
        return;
    }

    $session_id = $session_data['SessionID'];

    // Ticket data from the form.
    $ticket_data = array(
        'Ticket' => array(
            'Title'        => sanitize_text_field($_POST['ticket_title']),
            'Queue'        => 'Raw', // Example queue, adjust accordingly
            'StateID'      => 1, // Adjust as necessary
            'PriorityID'   => 3, // Adjust as necessary
            'CustomerUser' => get_option('znuny_customer_user'),
        ),
        'Article' => array(
            'CommunicationChannel' => 'Email',
            'Subject'              => sanitize_text_field($_POST['article_subject']),
            'Body'                 => sanitize_textarea_field($_POST['article_body']),
            'ContentType'          => '',
            'Charset'              => 'utf-8',
            'MimeType'             => 'text/plain',
        ),
    );

    // Log ticket request data
    echo '<h3>Ticket Request Data:</h3>';
    echo 'Request Body: ' . esc_html(json_encode(array(
        'SessionID' => $session_id,
        'Ticket'    => $ticket_data['Ticket'],
        'Article'   => $ticket_data['Article'],
    ))) . '<br>';

    // Create ticket API request.
    $ticket_response = wp_remote_post($domain . '/znuny/nph-genericinterface.pl/Webservice/unluckytech/Ticket', array(
        'body'    => json_encode(array(
            'SessionID' => $session_id,
            'Ticket'    => $ticket_data['Ticket'],
            'Article'   => $ticket_data['Article'],
        )),
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
    ));

    if (is_wp_error($ticket_response)) {
        echo 'Error submitting ticket: ' . esc_html($ticket_response->get_error_message());
        return;
    }

    // Log ticket response
    $ticket_status_code = wp_remote_retrieve_response_code($ticket_response);
    $ticket_body = wp_remote_retrieve_body($ticket_response);
    echo '<h3>Ticket Response Data:</h3>';
    echo 'Status Code: ' . esc_html($ticket_status_code) . '<br>';
    echo 'Response Body: ' . esc_html($ticket_body) . '<br>';

    $ticket_data = json_decode($ticket_body, true);

    // Output success message.
    if (isset($ticket_data['TicketNumber'])) {
        echo 'Ticket created successfully. Ticket Number: ' . esc_html($ticket_data['TicketNumber']);
    } else {
        echo 'Failed to create ticket.';
    }
}
?>

<!-- HTML form for submitting a ticket -->
<h1>Submit a Ticket</h1>
<form method="POST">
    <label for="ticket_title">Ticket Title:</label>
    <input type="text" id="ticket_title" name="ticket_title" required>

    <label for="article_subject">Subject:</label>
    <input type="text" id="article_subject" name="article_subject" required>

    <label for="article_body">Message:</label>
    <textarea id="article_body" name="article_body" required></textarea>

    <input type="submit" name="submit_ticket" value="Submit Ticket">
</form>
