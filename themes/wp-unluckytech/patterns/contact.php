<?php
/**
 * Title: Contact Me
 * Slug: unluckytech/contact
 * Categories: text, featured
 * Description: This page allows users to contact me directly via email.
 */
?>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {

    if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'contact_form_submit')) {
        $error_message = "Security check failed. Please try again.";

    } elseif (empty($_POST['cf-turnstile-response'])) {
        $error_message = "Please complete the CAPTCHA.";

    } else {
        $captcha_response = sanitize_text_field($_POST['cf-turnstile-response']);
        $captcha_secret   = get_option('cfturnstile_secret');

        $captcha_verify = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'body' => [
                'secret'   => $captcha_secret,
                'response' => $captcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            ],
        ]);

        if (is_wp_error($captcha_verify)) {
            $error_message = "CAPTCHA verification failed due to a network error. Please try again.";

        } else {
            $captcha_result = json_decode(wp_remote_retrieve_body($captcha_verify), true);

            if (empty($captcha_result['success'])) {
                $error_message = "CAPTCHA verification failed. Please try again.";

            } else {
                $firstname    = sanitize_text_field($_POST['firstname']);
                $lastname     = sanitize_text_field($_POST['lastname']);
                $custemail    = sanitize_email($_POST['custemail']);
                $custphone    = !empty($_POST['custphone']) ? sanitize_text_field($_POST['custphone']) : '';
                $service      = sanitize_text_field($_POST['service']);
                $inquiry_type = sanitize_text_field($_POST['inquiry_type']);
                $message      = sanitize_textarea_field($_POST['message']);
                $full_name    = $firstname . ' ' . $lastname;
                $subject      = 'New Inquiry from ' . $full_name;

                // Notify admin
                $admin_body  = '<p>New contact form submission:</p>';
                $admin_body .= '<p><strong>Name:</strong> '         . esc_html($full_name) . '</p>';
                $admin_body .= '<p><strong>Email:</strong> '        . esc_html($custemail) . '</p>';
                $admin_body .= '<p><strong>Phone:</strong> '        . esc_html($custphone ?: 'N/A') . '</p>';
                $admin_body .= '<p><strong>Service:</strong> '      . esc_html($service) . '</p>';
                $admin_body .= '<p><strong>Inquiry Type:</strong> ' . esc_html($inquiry_type) . '</p>';
                $admin_body .= '<p><strong>Message:</strong><br>'   . nl2br(esc_html($message)) . '</p>';

                wp_mail(
                    get_option('admin_email'),
                    $subject,
                    $admin_body,
                    ['Content-Type: text/html; charset=UTF-8']
                );

                // Create ticket
                $ticket_number = null;
                if (class_exists('UT_Ticket_Handler')) {
                    $handler = new UT_Ticket_Handler();
                    $ticket_number = $handler->create_ticket([
                        'user_id'      => is_user_logged_in() ? get_current_user_id() : 0,
                        'name'         => $full_name,
                        'email'        => $custemail,
                        'phone'        => $custphone,
                        'service'      => $service,
                        'inquiry_type' => $inquiry_type,
                        'subject'      => $subject,
                        'message'      => $message,
                    ]);
                }

                // Confirm to user
                if ($ticket_number) {
                    $site = get_bloginfo('name');
                    $confirm_body  = '<p>Hi ' . esc_html($firstname) . ',</p>';
                    $confirm_body .= '<p>Thanks for reaching out! Your support ticket has been created.</p>';
                    $confirm_body .= '<p><strong>Ticket #:</strong> ' . esc_html($ticket_number) . '</p>';
                    $confirm_body .= '<p>You can check the status of your ticket from your account page. We\'ll reply as soon as possible.</p>';
                    $confirm_body .= '<p>— ' . esc_html($site) . '</p>';
                    wp_mail(
                        $custemail,
                        '[' . $site . '] Ticket #' . $ticket_number . ' received',
                        $confirm_body,
                        ['Content-Type: text/html; charset=UTF-8']
                    );

                    $success_message = 'Your message has been sent! Your ticket number is <strong>' . esc_html($ticket_number) . '</strong>. Check your email for a confirmation.';
                } else {
                    $success_message = 'Your message has been sent! We\'ll get back to you soon.';
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
            <p><?php echo $success_message; ?></p>
        </div>
    <?php elseif (!empty($error_message)): ?>
        <div class="contact-error">
            <p><?php echo esc_html($error_message); ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(get_permalink()); ?>" class="contact-form">
        <input type="hidden" name="contact_form" value="1">
        <?php wp_nonce_field('contact_form_submit'); ?>

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

        <div class="captcha-container">
            <div class="cf-turnstile" data-sitekey="<?php echo esc_attr(get_option('cfturnstile_key')); ?>"></div>
        </div>

        <button type="submit" class="submit-button">Send Message</button>
    </form>
</div>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
