jQuery(document).ready(function($) {
    // Cache DOM elements to avoid repeated lookups.
    var $apiButton = $('#znuny-test-api');
    var $createTicketButton = $('#znuny-create-ticket');
    var $result = $('#znuny-test-result');
    var $ticketResult = $('#znuny-ticket-result');

    // Test API Connection
    $apiButton.on('click', function(e) {
        e.preventDefault();
        console.log('Test API button clicked'); // Debug message

        // Get and trim input values.
        let api_url = $('input[name="znuny_api_domain"]').val().trim();
        let user_login = $('input[name="znuny_user_login"]').val().trim();
        let password = $('input[name="znuny_password"]').val().trim();

        console.log('API URL:', api_url);
        console.log('User Login:', user_login);

        // Throttle API request
        if (api_url && user_login) {
            $.ajax({
                url: znuny_ajax_object.ajax_url, // Use localized variable for AJAX URL.
                method: 'POST',
                data: {
                    action: 'znuny_test_api',
                    api_url: api_url,
                    user_login: user_login,
                    password: password
                },
                success: function(response) {
                    console.log('API Response:', response);

                    let responseText = JSON.stringify(response, null, 2);
                    if (response.success) {
                        $result.html('<strong>API connection successful!</strong><br>' + responseText);
                    } else {
                        $result.html('<strong>API connection failed:</strong><br>' + responseText);
                    }
                },
                error: function(xhr) {
                    let errorResponse = JSON.stringify(xhr.responseText, null, 2);
                    $result.html('<strong>API connection failed:</strong> ' + errorResponse);
                }
            });
        }
    });

    // Create Test Ticket
    $createTicketButton.on('click', function(event) {
        event.preventDefault(); // Prevent default button action
        console.log('Create Ticket button clicked'); // Debug message

        // Define the ticket data structure using localized data
        var ticket_data = {
            UserLogin: znuny_ajax_object.user_login,  // Use localized variable
            Password: znuny_ajax_object.password,    // Use localized variable
            Ticket: {
                Title: 'Ticket created via REST API - minimal content',
                Queue: 'Raw', // Replace this with the correct queue
                StateID: 1,   // Open or any other state ID
                PriorityID: 3, // Normal priority
                CustomerUser: 'test'
            },
            Article: {
                CommunicationChannel: 'Email',
                Subject: 'Test Article created with new Ticket via REST API type Email',
                Body: 'email body.',
                Charset: 'utf-8',
                MimeType: 'text/plain'
            }
        };

        // AJAX call to create the test ticket
        $.ajax({
            url: znuny_ajax_object.ajax_url,  // Use localized variable for AJAX URL
            method: 'POST',
            data: {
                action: 'znuny_create_test_ticket',
                ticket_data: ticket_data
            },
            success: function(response) {
                console.log('Create Ticket Response:', response);

                let responseText = JSON.stringify(response, null, 2);
                if (response.success) {
                    $ticketResult.text('Ticket successfully created: ' + response.data.ticket_number);
                } else {
                    $ticketResult.text('Error creating ticket: ' + responseText);
                }
            },
            error: function(xhr) {
                let errorResponse = xhr.responseText ? JSON.stringify(xhr.responseText, null, 2) : 'Unknown error';
                $ticketResult.text('Error: ' + errorResponse);
            }
        });
    });
});
