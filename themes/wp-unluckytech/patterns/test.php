<?php
/**
 * Title: Test
 * Slug: unluckytech/test
 * Categories: text, featured
 * Description: This would be used for managing user accounts and email verification.
 */

// If this file is accessed directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary function to check if a plugin is active.
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

$current_user = wp_get_current_user();
$verification_code = get_user_meta($current_user->ID, 'email_verification_code', true);

// Handle email change request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_email'])) {
    $new_email = sanitize_email($_POST['new_email']);
    
    if (is_email($new_email) && $new_email !== $current_user->user_email) {
        // Generate verification code and send email
        $verification_code = wp_generate_password(6, false, false); // Generate a 6-digit code
        update_user_meta($current_user->ID, 'email_verification_code', $verification_code);
        
        $subject = 'Verify Your New Email Address';
        $message = 'Your email verification code is: ' . $verification_code;
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        
        if (wp_mail($new_email, $subject, $message, $headers)) {
            echo json_encode(['success' => true, 'message' => 'Verification code sent to your new email address!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send verification code.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid new email address that is different from the current one.']);
    }

    exit; // Stop processing further
}

// Handle email verification code submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verification_code'])) {
    if ($verification_code && $_POST['verification_code'] === $verification_code) {
        // Update user's email
        wp_update_user([
            'ID' => $current_user->ID,
            'user_email' => $_POST['new_email'],
        ]);
        delete_user_meta($current_user->ID, 'email_verification_code');
        echo json_encode(['success' => true, 'message' => 'Email verified and updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect verification code.']);
    }

    exit; // Stop processing further
}
?>

<div class="test-email-container">
    <h2>Change Email Address</h2>
    <form id="changeEmailForm" method="post">
        <label for="new_email">New Email Address:</label><br>
        <input type="email" id="new_email" name="new_email" required />
        <button type="submit" name="change_email" class="test-email-button">Send Verification Code</button>
    </form>

    <div id="email-verification" style="display: none;">
        <h2>Verify New Email</h2>
        <form id="verificationForm" method="post">
            <label for="verification_code">Verification Code:</label><br>
            <input type="text" id="verification_code" name="verification_code" required />
            <input type="hidden" name="new_email" id="hidden_new_email" />
            <button type="submit" class="test-email-button">Verify Email</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const changeEmailForm = document.getElementById('changeEmailForm');
        const verificationForm = document.getElementById('verificationForm');

        changeEmailForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page refresh

            const newEmail = document.getElementById('new_email').value;

            // Send AJAX request to change email
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo esc_url($_SERVER['REQUEST_URI']); ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                const response = JSON.parse(xhr.responseText);
                alert(response.message); // Show response message
                if (response.success) {
                    document.getElementById('email-verification').style.display = 'block'; // Show verification code input
                    document.getElementById('hidden_new_email').value = newEmail; // Set hidden new email
                }
            };
            xhr.send('change_email=1&new_email=' + encodeURIComponent(newEmail)); // Send new email via POST
        });

        verificationForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page refresh

            const verificationCode = document.getElementById('verification_code').value;
            const newEmail = document.getElementById('hidden_new_email').value;

            // Send AJAX request to verify email
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo esc_url($_SERVER['REQUEST_URI']); ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                const response = JSON.parse(xhr.responseText);
                alert(response.message); // Show response message
                if (response.success) {
                    document.getElementById('verification_code').value = ''; // Clear input
                    document.getElementById('email-verification').style.display = 'none'; // Hide verification section
                }
            };
            xhr.send('verification_code=' + encodeURIComponent(verificationCode) + '&new_email=' + encodeURIComponent(newEmail)); // Send verification code via POST
        });
    });
</script>