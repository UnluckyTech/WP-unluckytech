<?php
// Include the Znuny_API class from api.php
require_once 'api.php';

// Create an instance of the Znuny_API class
$znuny_api = new Znuny_API();

// Call the authenticate method to generate the SessionID
$session_id = $znuny_api->authenticate();

// Use the SessionID as needed
if ($session_id) {
    // Store the SessionID for later use
    session_start();
    $stored_session_id = $session_id; // Local variable (if needed)
    $_SESSION['znuny_session_id'] = $stored_session_id; // Store in session variable
    
    // You can perform other actions with the stored session ID here
    // For example, make another API call with this session ID
    
    // Optionally log it for debugging
    // error_log("Session ID retrieved and stored: " . $stored_session_id); 
    
} else {
    // Handle authentication failure
    error_log("Failed to authenticate and retrieve SessionID."); // Log error instead of showing to user
}
?>
