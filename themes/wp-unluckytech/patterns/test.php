<?php
/**
 * Title: Invoices
 * Slug: unluckytech/test
 * Categories: text, featured
 * Description: This file manages and displays user invoices.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$turnstile_site_key = get_option('cfturnstile_key'); // Fetch the site key from your database

// Initialize log array
$logs = array();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['test_form'])) {
    // Log the form submission
    $logs[] = "Form submitted.";

    // Check if CAPTCHA response exists
    if (empty($_POST['cf-turnstile-response'])) {
        $error_message = "Please complete the CAPTCHA.";
        $logs[] = "CAPTCHA response is empty.";
    } else {
        // Directly get the token from POST data
        $token = $_POST['cf-turnstile-response'];
        $logs[] = "CAPTCHA token received: " . esc_html($token);

        // Prepare to verify the token
        $captcha_secret = get_option('cfturnstile_secret'); // Fetch the secret key from your database
        $logs[] = "Secret key used: " . esc_html($captcha_secret); // Log the secret key for debugging (remove after debugging)

        // Verify CAPTCHA with Cloudflare Turnstile
        $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
            'body' => array(
                'secret' => $captcha_secret, // Secret key for verification
                'response' => $token, // Use the token directly here
                'remoteip' => $_SERVER['REMOTE_ADDR'], // Optional: Include the user's IP address
            )
        ));

        // Handle response errors or failures
        if (is_wp_error($response)) {
            $error_message = "CAPTCHA verification failed due to a network error. Please try again.";
            $logs[] = "CAPTCHA verification network error: " . esc_html($response->get_error_message());
        } else {
            $response_body = wp_remote_retrieve_body($response);
            $captcha_result = json_decode($response_body, true);

            // Log the API response
            $logs[] = "Cloudflare response: " . print_r($captcha_result, true);

            if (!isset($captcha_result['success']) || !$captcha_result['success']) {
                $error_message = "CAPTCHA verification failed. Please try again.";
                $logs[] = "CAPTCHA verification failed.";
            } else {
                // CAPTCHA passed, form processing continues
                $input_text = sanitize_text_field($_POST['input_text']);
                $success_message = "Form submitted successfully with input: " . esc_html($input_text);
                $logs[] = "Form processed successfully with input: " . esc_html($input_text);
            }
        }
    }
}



?>

<!-- Simple Form Layout -->
<div class="test-container">
    <h2>Test Form with Cloudflare CAPTCHA</h2>

    <?php if (!empty($success_message)): ?>
        <div class="test-success">
            <p><?php echo esc_html($success_message); ?></p>
        </div>
    <?php elseif (!empty($error_message)): ?>
        <div class="test-error">
            <p><?php echo esc_html($error_message); ?></p>
        </div>
    <?php endif; ?>

    <!-- Display Logs -->
    <?php if (!empty($logs)): ?>
        <div class="test-logs">
            <h3>Logs</h3>
            <ul>
                <?php foreach ($logs as $log): ?>
                    <li><?php echo esc_html($log); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(get_permalink()); ?>" class="test-form">
        <input type="hidden" name="test_form" value="1">

        <!-- Simple Text Input -->
        <div class="test-group">
            <label for="input_text">Your Input</label>
            <input type="text" id="input_text" name="input_text" required>
        </div>

        <!-- Cloudflare Turnstile CAPTCHA -->
        <div class="captcha-container">
            <div class="cf-turnstile" data-sitekey="<?php echo esc_attr($turnstile_site_key); ?>" data-callback="onCaptchaCompleted"></div>
        </div>

        <input type="hidden" id="cf-turnstile-response" name="cf-turnstile-response" required>

        <!-- Submit Button (Disabled until CAPTCHA is verified) -->
        <div class="test-group">
            <button type="submit" id="test-button" class="test-button" disabled>Submit</button>
        </div>
    </form>
</div>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const turnstileResponseInput = document.getElementById('cf-turnstile-response');
    const submitButton = document.getElementById('test-button');

    // Callback function when CAPTCHA is completed
    window.onCaptchaCompleted = function(token) {
        turnstileResponseInput.value = token;
        submitButton.disabled = false; // Enable the submit button
    };

    // Disable the button again if CAPTCHA response is cleared
    turnstileResponseInput.addEventListener('input', function() {
        if (!this.value) {
            submitButton.disabled = true; // Disable the submit button if response is cleared
        }
    });
});
</script>
