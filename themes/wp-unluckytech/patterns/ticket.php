<?php
/**
 * Title: Ticket Submission
 * Slug: unluckytech/ticket
 * Categories: text, featured
 * Description: This page allows users to check the status of a ticket.
 */

// Fetch the necessary options from your WordPress settings
$domain = get_option('znuny_api_domain');
$user_login = get_option('znuny_user_login');
$password = get_option('znuny_password');

$ticket_number = '';
$success_message = '';
$error_message = '';

// Function to get Session ID
function get_session_id($domain, $user_login, $password) {
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
        return false; // Error in getting session ID
    }

    $session_data = json_decode(wp_remote_retrieve_body($session_response), true);
    return isset($session_data['SessionID']) ? $session_data['SessionID'] : false;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_number'])) {
    // Sanitize and define the ticket number variable
    $ticket_number = sanitize_text_field($_POST['ticket_number']);

    // Generate the Session ID
    $session_id = get_session_id($domain, $user_login, $password);
    
    if ($session_id === false) {
        $error_message = 'Failed to retrieve Session ID.';
    } else {
        // Construct the API request URL to get Ticket ID
        $api_url = sprintf('%s/znuny/nph-genericinterface.pl/Webservice/unluckytech/Ticket?SessionID=%s&TicketNumber=%s', 
            $domain, 
            $session_id, 
            $ticket_number
        );

        // Make the GET request to check ticket status
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            $error_message = 'Error retrieving ticket status: ' . esc_html($response->get_error_message());
        } else {
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            if (isset($response_body['Error'])) {
                $error_message = 'Error: ' . esc_html($response_body['Error']);
            } elseif (isset($response_body['TicketID']) && is_array($response_body['TicketID'])) {
                // Extract TicketID
                $ticket_id = $response_body['TicketID'][0];

                // Construct the new API request URL to get detailed ticket info
                $details_url = sprintf('%s/znuny/nph-genericinterface.pl/Webservice/unluckytech/Ticket/%s?SessionID=%s', 
                    $domain, 
                    $ticket_id, 
                    $session_id
                );

                // Make the GET request to get detailed ticket information
                $details_response = wp_remote_get($details_url);

                if (is_wp_error($details_response)) {
                    $error_message = 'Error retrieving ticket details: ' . esc_html($details_response->get_error_message());
                } else {
                    // Decode the JSON response
                    $ticket_details = json_decode(wp_remote_retrieve_body($details_response), true);
                    
                    if (isset($ticket_details['Ticket']) && is_array($ticket_details['Ticket'])) {
                        $ticket_info = $ticket_details['Ticket'][0];
                
                        // Prepare the success message in a structured format
                        $success_message = sprintf(
                            "<h3>Ticket Number: %s</h3>
                            <table>
                                <tr>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Last Updated</th>
                                    <th>Status</th>
                                </tr>
                                <tr>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td>%s</td>
                                </tr>
                            </table>",
                            esc_html($ticket_info['TicketNumber']),
                            esc_html($ticket_info['Type']),
                            esc_html($ticket_info['Title']),
                            esc_html($ticket_info['Changed']),
                            esc_html($ticket_info['StateType'])
                        );
                    } else {
                        // Output the raw response body if the structure is unexpected
                        $error_message = 'Unexpected response structure: ' . esc_html(wp_remote_retrieve_body($details_response));
                    }
                }
            } else {
                // Output the raw response body if the structure is unexpected
                $error_message = 'Unexpected response structure: ' . esc_html(wp_remote_retrieve_body($response));
            }
        }
    }
}
?>

<div id="ticket-status" class="tab">
    <h2>Check Ticket Status</h2>
    <?php if (!empty($success_message)): ?>
        <div class="contact-success">
            <div class="table-responsive">
                <p><?php echo $success_message; ?></p>
            </div>
        </div>
    <?php elseif (!empty($error_message)): ?>
        <div class="contact-error">
            <p><?php echo esc_html($error_message); ?></p>
        </div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="contact-group">
            <label for="ticket_number">Enter Ticket Number</label>
            <input type="text" id="ticket_number" name="ticket_number" required>
        </div>
        <button type="submit" class="submit-button">Check Status</button>
    </form>
</div>
