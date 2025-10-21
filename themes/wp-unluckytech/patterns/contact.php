<?php
/**
 * Title: Contact Me
 * Slug: unluckytech/contact
 * Categories: text, featured
 * Description: This page allows users to contact me directly via email.
 */
?>

<?php

$domain = get_option('znuny_api_domain');
$user_login = get_option('znuny_user_login');
$password = get_option('znuny_password');
$session_id = session_id(); // Will work fine if session was started via functions.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {

    if (empty($_POST['cf-turnstile-response'])) {
        $error_message = "Please complete the CAPTCHA.";
    } else {
        $captcha_response = sanitize_text_field($_POST['cf-turnstile-response']);
        $_SESSION['captcha_response'] = $captcha_response;

        // Close session before making any HTTP calls
        session_write_close();

        $captcha_secret = get_option('cfturnstile_secret');

        $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
            'body' => array(
                'secret' => $captcha_secret,
                'response' => $captcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            )
        ));

        if (is_wp_error($response)) {
            $error_message = "CAPTCHA verification failed due to a network error. Please try again.";
        } else {
            $response_body = wp_remote_retrieve_body($response);
            $captcha_result = json_decode($response_body, true);

            if (!isset($captcha_result['success']) || !$captcha_result['success']) {
                $error_message = "CAPTCHA verification failed. Please try again.";
            } else {
                // Process form data if CAPTCHA passed
                $firstname = sanitize_text_field($_POST['firstname']);
                $lastname = sanitize_text_field($_POST['lastname']);
                $custemail = sanitize_email($_POST['custemail']);
                $custphone = !empty($_POST['custphone']) ? sanitize_text_field($_POST['custphone']) : 'N/A';
                $service = sanitize_text_field($_POST['service']);
                $inquiry_type = sanitize_text_field($_POST['inquiry_type']);
                $message = sanitize_textarea_field($_POST['message']);

                $to = 'stawse@unluckytech.com';
                $subject = 'New Inquiry from ' . $firstname . ' ' . $lastname;
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $body = "<p>You have received a new message from your contact form:</p>";
                $body .= "<p><strong>Name:</strong> " . esc_html($firstname) . " " . esc_html($lastname) . "</p>";
                $body .= "<p><strong>Email:</strong> " . esc_html($custemail) . "</p>";
                $body .= "<p><strong>Phone (optional):</strong> " . esc_html($custphone) . "</p>";
                $body .= "<p><strong>Service:</strong> " . esc_html($service) . "</p>";
                $body .= "<p><strong>Inquiry Type:</strong> " . esc_html($inquiry_type) . "</p>";
                $body .= "<p><strong>Message:</strong> " . nl2br(esc_html($message)) . "</p>";

                // Send email
                if (wp_mail($to, $subject, $body, $headers)) {
                    // Authenticate and get Session ID
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
                        $error_message = 'Error retrieving session ID: ' . esc_html($session_response->get_error_message());
                    } else {
                        $session_data = json_decode(wp_remote_retrieve_body($session_response), true);
                        if (!isset($session_data['SessionID'])) {
                            $error_message = 'Failed to retrieve Session ID.';
                        } else {
                            $session_id = $session_data['SessionID'];

                            // Create ticket data
                            $ticket_data = array(
                                'Ticket' => array(
                                    'Title'        => 'Inquiry from ' . $firstname . ' ' . $lastname,
                                    'Queue'        => $service, // Example queue, adjust accordingly
                                    'StateID'      => 1, // Adjust as necessary
                                    'PriorityID'   => 3, // Adjust as necessary
                                    'CustomerUser' => $custemail,
                                    'Type'         => $inquiry_type, // Set the Type based on the selected service
                                ),
                                'Article' => array(
                                    'CommunicationChannel' => 'Email',
                                    'Subject'              => 'New Inquiry from ' . $firstname . ' ' . $lastname,
                                    'Body'                 => nl2br(esc_html($message)),
                                    'ContentType'          => '',
                                    'Charset'              => 'utf-8',
                                    'MimeType'             => 'text/plain',
                                ),
                            );

                            // Create ticket API request
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
                                $error_message = "Your message has been sent, but there was an issue generating a ticket. Please try again later.";
                            } else {
                                $ticket_data = json_decode(wp_remote_retrieve_body($ticket_response), true);
                                if (isset($ticket_data['TicketNumber'])) {
                                    $success_message = "Your message has been sent successfully, and a ticket has been generated! Ticket Number: " . esc_html($ticket_data['TicketNumber']);
                                } else {
                                    $error_message = "Your message has been sent, but there was an issue generating a ticket. Please try again later.";
                                }
                            }
                        }
                    }
                } else {
                    $error_message = "There was an issue sending your message. Please try again later.";
                }
            }
        }
    }
}
?>

<!-- Contact Page Layout -->
<div id="contact" class="tab active">
    <?php if (!empty($success_message)): ?>
        <div class="contact-success">
            <p><?php echo esc_html($success_message); ?></p>
        </div>
    <?php elseif (!empty($error_message)): ?>
        <div class="contact-error">
            <p><?php echo esc_html($error_message); ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(get_permalink()); ?>" class="contact-form">
        <input type="hidden" name="contact_form" value="1">
        
        <div class="contact-group two-column">
            <div class="half-width">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="half-width">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
        </div>

        <div class="contact-group">
            <label for="custemail">Your Email</label>
            <input type="email" id="custemail" name="custemail" required>
        </div>

        <div class="contact-group">
            <label for="custphone">Your Phone (optional)</label>
            <input type="text" id="custphone" name="custphone">
        </div>

        <div class="contact-group two-column">
            <div class="half-width">
                <label for="service">Service</label>
                <select id="service" name="service" required>
                    <option value="Web Development">Web Development</option>
                    <option value="System Configuration">System Configuration</option>
                    <option value="Server Management">Server Management</option>
                    <option value="Technical Consultation">Technical Consultation</option>
                    <option value="Custom PC Builds">Custom PC Builds</option>
                    <option value="IT Support">IT Support</option>
                    <option value="Custom Request">Custom Request</option>
                </select>
            </div>
            <div class="half-width">
                <label for="inquiry_type">Inquiry Type</label>
                <select id="inquiry_type" name="inquiry_type" required>
                    <option value="General Inquiry">General Inquiry</option>
                    <option value="Service Quote">Service Quote</option>
                    <option value="Support">Support</option>
                    <option value="Feedback">Feedback</option>
                </select>
            </div>
        </div>

        <div class="contact-group">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" required></textarea>
        </div>

        <!-- Cloudflare Turnstile -->
        <div class="captcha-container">
            <div class="cf-turnstile" data-sitekey="<?php echo esc_attr(get_option('cfturnstile_key')); ?>"></div>
        </div>

        <button type="submit" class="submit-button">Send Message</button>
    </form>
</div>


<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const turnstileResponseInput = document.getElementById('cf-turnstile-response');
    const submitButton = document.getElementById('contact-button');

    // Callback function when CAPTCHA is completed
    window.onCaptchaCompleted = function(token) {
        turnstileResponseInput.value = token;
        submitButton.disabled = false; // Enable the submit button
    };
});
</script>
