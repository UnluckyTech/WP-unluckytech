jQuery(document).ready(function($) {
    // Test API Connection
    $('#znuny-test-api').on('click', function(e) {
        e.preventDefault();
        console.log('Test API button clicked'); // Debug message

        // Get and trim input values.
        let api_url = $('input[name="znuny_api_domain"]').val().trim();
        let user_login = $('input[name="znuny_user_login"]').val().trim();
        let password = $('input[name="znuny_password"]').val().trim();

        console.log('API URL:', api_url);
        console.log('User Login:', user_login);
        console.log('Password:', password);

        $.ajax({
            url: ajaxurl, // The built-in AJAX URL.
            method: 'POST',
            data: {
                action: 'znuny_test_api',
                api_url: api_url,
                user_login: user_login,
                password: password,
            },
            success: function(response) {
                console.log('Raw response:', response); // Log the raw response for debugging
                
                // Display response as string to avoid "[object Object]"
                let responseText = JSON.stringify(response, null, 2);
                console.log('Formatted response:', responseText); // Log formatted response
                
                if (response.success) {
                    $('#znuny-test-result').html('<strong>API connection successful!</strong><br>' + responseText);
                } else {
                    let errorMessage = '<strong>API connection failed:</strong><br>';
                    $.each(response.data, function(key, value) {
                        errorMessage += key + ': ' + value + '<br>';
                    });
                    $('#znuny-test-result').html(errorMessage + '<br>Full response:<br>' + responseText);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', error); // Debug error
                // Convert and display full error response
                let errorResponse = JSON.stringify(xhr.responseText, null, 2);
                $('#znuny-test-result').html('<strong>API connection failed:</strong> ' + error + '<br>Full response:<br>' + errorResponse);
            }
        });
    });

    // Create Test Ticket
    $('#znuny-create-ticket').on('click', function(event) {
        event.preventDefault(); // Prevent default button action
        console.log('Create Ticket button clicked'); // Debug message

        // Define the ticket data structure using predefined variables
        var ticket_data = {
            UserLogin: '<?php echo esc_js($user_login); ?>', // Use the predefined user login
            Password: '<?php echo esc_js($password); ?>', // Use the predefined password
            Ticket: {
                Title: 'Ticket created via REST API - minimal content',
                Queue: 'Raw', // Replace this with the correct queue
                StateID: 1, // Open or any other state ID
                PriorityID: 3, // Normal priority
                CustomerUser: 'test' // A test user or real user ID
            },
            Article: {
                CommunicationChannel: 'Email',
                Subject: 'Test Article created with new Ticket via REST API type Email',
                Body: 'email body.',
                ContentType: '', // You can specify if needed
                Charset: 'utf-8',
                MimeType: 'text/plain'
            }
        };

        // AJAX call to create the test ticket
        $.ajax({
            url: ajaxurl, // The built-in AJAX URL
            method: 'POST',
            data: {
                action: 'znuny_create_test_ticket',
                ticket_data: ticket_data
            },
            success: function(response) {
                console.log('Response from server:', response); // Debug response

                // Display response as string
                let responseText = JSON.stringify(response, null, 2);
                console.log('Formatted response:', responseText); // Log formatted response

                if (response.success) {
                    $('#znuny-ticket-result').text('Ticket successfully created: ' + response.data.ticket_number);
                } else {
                    let errorMessage = 'Error creating ticket: ';
                    errorMessage += responseText; // Display full error message
                    $('#znuny-ticket-result').text(errorMessage);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', error); // Debug error
                // Parse the error response and extract relevant information
                try {
                    let errorResponse = JSON.stringify(JSON.parse(xhr.responseText), null, 2);
                    $('#znuny-ticket-result').text('Error: ' + errorResponse);
                } catch (e) {
                    $('#znuny-ticket-result').text('Error: ' + xhr.responseText);
                }
            }
        });
    });
});
