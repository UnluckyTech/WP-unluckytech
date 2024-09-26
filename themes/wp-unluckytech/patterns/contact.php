<?php
/**
 * Title: Contact Me
 * Slug: unluckytech/contact
 * Categories: text, featured
 * Description: This page allows users to contact me directly via email.
 */
?>

<?php
// If this file is accessed directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

$turnstile_site_key = get_option('cfturnstile_site_key');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contact_form'])) {
    // Check if CAPTCHA response exists
    if (empty($_POST['cf_turnstile_response'])) {
        $error_message = "Please complete the CAPTCHA.";
    } else {
        $captcha_response = sanitize_text_field($_POST['cf_turnstile_response']);
        $captcha_secret = get_option('cfturnstile_secret_key'); // Secret key from Cloudflare Turnstile
        
        // Verify CAPTCHA
        $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
            'body' => array(
                'secret' => $captcha_secret,
                'response' => $captcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            )
        ));
        
        $response_body = wp_remote_retrieve_body($response);
        $captcha_result = json_decode($response_body, true);

        if (!$captcha_result['success']) {
            $error_message = "CAPTCHA verification failed. Please try again.";
        } else {
            // CAPTCHA passed, continue processing the form
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
                $success_message = "Your message has been sent successfully!";
            } else {
                $error_message = "There was an issue sending your message. Please try again later.";
            }
        }
    }
}
?>

<!-- Contact Page Layout -->
<div class="contact-container">

    <!-- Contact Banner -->
    <div class="contact-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="contact-banner-overlay">
            <h1 class="contact-title">Contact Me</h1>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div class="contact-content">
        <div class="contact-form-container">
            <h2>Get in Touch</h2>
            <p>If you have any questions or would like to reach out, feel free to send me a message!</p>

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

                <!-- First Name and Last Name (Same Row) -->
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

                <!-- Email Field -->
                <div class="contact-group">
                    <label for="custemail">Your Email</label>
                    <input type="email" id="custemail" name="custemail" required>
                </div>

                <!-- Phone Field (optional) -->
                <div class="contact-group">
                    <label for="custphone">Your Phone (optional)</label>
                    <input type="text" id="custphone" name="custphone">
                </div>

                <!-- Service and Inquiry Type (Same Row) -->
                <div class="contact-group two-column">
                    <div class="half-width">
                        <label for="service">Service</label>
                        <select id="service" name="service" required>
                            <option value="web-development">Web Development</option>
                            <option value="seo">SEO Services</option>
                            <option value="content-writing">Content Writing</option>
                            <option value="graphic-design">Graphic Design</option>
                        </select>
                    </div>
                    <div class="half-width">
                        <label for="inquiry_type">Inquiry Type</label>
                        <select id="inquiry_type" name="inquiry_type" required>
                            <option value="quote">Quote</option>
                            <option value="support">Support</option>
                            <option value="general">General Inquiry</option>
                        </select>
                    </div>
                </div>

                <!-- Message Field -->
                <div class="contact-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>

                <div class="captcha-container">
                    <div class="cf-turnstile" data-sitekey="<?php echo esc_attr(get_option('cfturnstile_key')); ?>" data-callback="onCaptchaCompleted"></div>
                </div>
                <input type="hidden" id="cf-turnstile-response" name="cf_turnstile_response" required>

                <!-- Submit Button -->
                <div class="contact-group">
                    <button type="submit" class="contact-button">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>

<script>
function onCaptchaCompleted(token) {
    document.getElementById('cf-turnstile-response').value = token;
}
</script>