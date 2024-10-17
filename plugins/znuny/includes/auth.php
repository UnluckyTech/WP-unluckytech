<?php
// Include the Znuny_API class from api.php
require_once 'api.php';

// Create an instance of the Znuny_API class
$znuny_api = new Znuny_API();

// Check if a session ID already exists
$stored_session_id = get_option('znuny_session_id');

if (!$stored_session_id) {
    // Call the authenticate method to generate the SessionID
    $session_id = $znuny_api->authenticate();

    // Use the SessionID as needed
    if ($session_id) {
        // Store the SessionID for later use
        update_option('znuny_session_id', $session_id); // Use WordPress option instead of session
        
        // You can perform other actions with the stored session ID here
        // error_log("Session ID retrieved and stored: " . $stored_session_id); 
    } else {
        // Handle authentication failure
        error_log("Failed to authenticate and retrieve SessionID."); // Log error instead of showing to user
    }
} else {
    // Optionally log that an existing session is being used
    // error_log("Using existing Session ID: " . $stored_session_id);
}
?>