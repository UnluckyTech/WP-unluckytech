<?php
// Ticket class to handle ticket creation and management.
class Znuny_Ticket {
    // Method to create a new ticket
    public function create_new_ticket($customer_user, $subject, $body) {
        // Construct the ticket data structure
        $ticket_data = array(
            "Ticket" => array(
                "Title"        => $subject,
                "Queue"        => "Raw",
                "StateID"      => 1,
                "PriorityID"   => 3,
                "CustomerUser" => $customer_user, // Use the CustomerUser from the input
            ),
            "Article" => array(
                "CommunicationChannel" => "Email",
                "Subject"              => $subject,
                "Body"                 => $body,
                "ContentType"          => "",
                "Charset"              => "utf-8",
                "MimeType"             => "text/plain",
            ),
        );

        // Debugging: Log the ticket data
        error_log('Znuny Ticket Data: ' . print_r($ticket_data, true));

        // Call the Znuny API to create the ticket
        $response = $this->send_ticket_request($ticket_data);

        // Debugging: Log the API response
        if (is_wp_error($response)) {
            error_log('Znuny API Error: ' . $response->get_error_message());
            return false; // Return false on error
        }

        error_log('Znuny API Response: ' . wp_remote_retrieve_body($response));

        return $response; // Return the response from the API
    }

    // Method to send the ticket request to the Znuny API
    private function send_ticket_request($data) {
        $api_url = esc_url(get_option('znuny_api_domain')) . '/znuny/nph-genericinterface.pl/Webservice/unluckytech/Ticket';
        
        // Debugging: Log the API URL
        error_log('Znuny API URL: ' . $api_url);

        $response = wp_remote_post($api_url, array(
            'body'    => json_encode($data),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
        ));

        // Check for errors
        if (is_wp_error($response)) {
            return false; // Return false on error
        }

        // Return the API response
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}
