<?php
/**
 * Title: Account
 * Slug: unluckytech/account
 * Categories: text, featured
 * Description: This would be used for the account page of the website.
 */

// If this file is accessed directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Check if the user is logged in, otherwise redirect to the login page
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

// Include necessary function to check if a plugin is active.
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

$current_user = wp_get_current_user();
$verification_code = get_user_meta($current_user->ID, 'email_verification_code', true);

// Handle profile update request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['profile_update'])) {
    // Only proceed if user is logged in
    if (!is_user_logged_in()) {
        wp_redirect(wp_login_url());
        exit;
    }

    $user_id = get_current_user_id();
    $current_user = wp_get_current_user();

    // Get old values
    $old_nickname = $current_user->nickname;
    $old_first_name = $current_user->first_name;
    $old_last_name = $current_user->last_name;

    // Get new values from POST
    $new_nickname = sanitize_text_field($_POST['nickname']);
    $new_first_name = sanitize_text_field($_POST['first_name']);
    $new_last_name = sanitize_text_field($_POST['last_name']);

    // Prepare changes array
    $changes = array();

    // Update user data and check for changes
    $user_data = array('ID' => $user_id);

    if ($old_nickname !== $new_nickname) {
        $user_data['nickname'] = $new_nickname;
        $changes['Nickname'] = array('old' => $old_nickname, 'new' => $new_nickname);
    }

    if ($old_first_name !== $new_first_name) {
        $user_data['first_name'] = $new_first_name;
        $changes['First Name'] = array('old' => $old_first_name, 'new' => $new_first_name);
    }

    if ($old_last_name !== $new_last_name) {
        $user_data['last_name'] = $new_last_name;
        $changes['Last Name'] = array('old' => $old_last_name, 'new' => $new_last_name);
    }

    if (!empty($changes)) {
        // Update user data
        wp_update_user($user_data);

        // Send email notification
        $to = $current_user->user_email;
        $subject = 'Account Information Changed';
        $message = 'Hello ' . $current_user->display_name . ",<br><br>The following changes were made to your account:<br><br>";

        foreach ($changes as $field => $values) {
            $message .= '<strong>' . $field . '</strong> changed from "' . esc_html($values['old']) . '" to "' . esc_html($values['new']) . '".<br>';
        }

        $message .= "<br>If you did not make these changes, please contact us immediately.<br><br>Thank you,<br>Your Website Team";

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($to, $subject, $message, $headers);
    }

    // Redirect back to account page
    wp_redirect(get_permalink());
    exit;
}

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
        // Get the old email
        $old_email = $current_user->user_email;
        $new_email = sanitize_email($_POST['new_email']);

        // Update user's email
        wp_update_user([
            'ID' => $current_user->ID,
            'user_email' => $new_email,
        ]);
        
        // Mark the email as verified
        update_user_meta($current_user->ID, 'email_verified', 1);

        // Remove the verification code from user meta
        delete_user_meta($current_user->ID, 'email_verification_code');

        // Send email notification to the old email address
        $subject = 'Your Email Address Has Been Changed';
        $message = 'Hello ' . $current_user->display_name . ",<br><br>Your email address has been changed from <strong>" . esc_html($old_email) . "</strong> to <strong>" . esc_html($new_email) . "</strong>.<br><br>If you did not make this change, please contact us immediately.<br><br>Thank you,<br>Your Website Team";

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($old_email, $subject, $message, $headers);

        // Respond with success message
        echo json_encode(['success' => true, 'message' => 'Email verified and updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect verification code.']);
    }

    exit; // Stop processing further
}

// Handle password change request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = sanitize_text_field($_POST['current_password']);
    $new_password = sanitize_text_field($_POST['new_password']);

    if (wp_check_password($current_password, $current_user->user_pass, $current_user->ID)) {
        wp_set_password($new_password, $current_user->ID);

        // Send email notification
        $to = $current_user->user_email;
        $subject = 'Your Password Has Been Changed';
        $message = 'Hello ' . $current_user->display_name . ",<br><br>Your account password has been changed.<br><br>If you did not make this change, please reset your password immediately or contact us.<br><br>Thank you,<br>Your Website Team";

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($to, $subject, $message, $headers);

        echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    }

    exit; // Stop processing further
}

?>

<div class="main-container">
    <!-- Account Banner -->
    <div class="main-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/account/account.webp');">
        <div class="main-banner-overlay">
            <h1 class="main-title">Account</h1>
        </div>
    </div> 
    <div class="account-content">
        <div class="mobile-settings">
        <button id="settingsButton">Settings</button>
        </div>
        <div class="account-sidebar">
        <ul>
            <!-- General Section -->
            <li class="sidebar-section-title">General</li>
            <li><a href="#" data-section="profile-section">Profile</a></li>
            <li><a href="#" data-section="notifications-section">Notifications</a></li>

            <!-- Security Section -->
            <li class="sidebar-section-title">Security</li>
            <li><a href="#" data-section="email-section">Email</a></li>
            <li><a href="#" data-section="password-section">Password</a></li>

            <!-- Purchase Section -->
            <li class="sidebar-section-title">Quote & Purchase</li>
            <li><a href="#" data-section="quote-section">Quotes</a></li>
            <li><a href="#" data-section="invoice-section">Invoices</a></li>
            
        </ul>
            <div class="invoice-portal">
                <?php echo do_shortcode('[client_portal label="Client Portal" sso="true"]'); ?>
            </div>
        </div>

        <div class="account-display">
            <!-- Profile Section -->
            <div class="account-section" id="profile-section" style="display: block;">
                <h2 class="acc-title">Profile</h2>
                <?php if ($current_user->exists()) : ?>
                    <form id="profileForm" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="save_profile">
                        
                        <!-- Profile Picture -->
                        <?php $profile_picture = get_avatar(get_current_user_id()); ?>
                        <div class="profile-picture">
                            <?php echo $profile_picture ?: '<a href="https://en.gravatar.com/" target="_blank">Set your profile picture on Gravatar</a>'; ?>
                        </div>

                        <!-- Nickname -->
                        <div class="acc-group">
                            <label for="nickname">Nickname:</label>
                            <input type="text" id="nickname" name="nickname" value="<?php echo esc_attr($current_user->nickname); ?>" />
                        </div>

                        <!-- First Name -->
                        <div class="acc-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($current_user->user_firstname); ?>" />
                        </div>

                        <!-- Last Name -->
                        <div class="acc-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($current_user->user_lastname); ?>" />
                        </div>

                        <!-- Save Button -->
                        <p><button class="btn btn-save" type="submit">Save Profile</button></p>
                    </form>
                <?php endif; ?>
            </div>


            <!-- Notifications Section -->
            <div class="account-section" id="notifications-section" style="display: none;">
	            <!-- wp:pattern {"slug":"unluckytech/notification"} /-->
            </div>

            <!-- Email Section -->
            <div class="account-section" id="email-section" style="display: none;">
                <h2 class="acc-title">Change Email</h2>
                
                <!-- Current Email Display -->
                <p class="current-email">Your current email: <?php echo esc_html($current_user->user_email); ?></p>

                <!-- Change Email Form -->
                <form id="changeEmailForm" method="post">
                    <div class="acc-group">
                        <label for="new_email">New Email Address:</label>
                        <input type="email" id="new_email" name="new_email" required />
                    </div>
                    <button type="submit" name="change_email" class="btn btn-submit">Send Verification Code</button>
                </form>

                <!-- Email Verification Section -->
                <div id="email-verification" style="display: none;">
                    <h2>Verify New Email</h2>
                    <form id="verificationForm" method="post">
                        <div class="acc-group">
                            <label for="verification_code">Verification Code:</label>
                            <input type="text" id="verification_code" name="verification_code" required />
                        </div>
                        <input type="hidden" name="new_email" id="hidden_new_email" />
                        <button type="submit" class="btn btn-submit">Verify Email</button>
                    </form>
                </div>
            </div>


            <!-- Password Section -->
            <div class="account-section" id="password-section" style="display: none;">
                <h2 class="acc-title">Change Password</h2>
                <form id="changePasswordForm" method="post" class="password-form">
                    <div class="acc-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required />
                        <div class="checkbox-container">
                            <label for="toggleCurrentPassword">Show Password</label>
                            <input type="checkbox" id="toggleCurrentPassword"> 
                            
                        </div>
                    </div>

                    <div class="acc-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required />
                        <div class="checkbox-container">
                            <label for="toggleNewPassword">Show Password</label>
                            <input type="checkbox" id="toggleNewPassword">
                        </div>
                    </div>

                    <div class="acc-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required />
                    </div>

                    <button type="submit" name="change_password" class="test-email-button">Change Password</button>
                </form>
            </div>


            <!-- Quote Section -->
            <div class="account-section" id="quote-section" style="display: none;">
	            <!-- wp:pattern {"slug":"unluckytech/quote"} /-->
            </div>

            <!-- Invoices Section -->
            <div class="account-section" id="invoice-section" style="display: none;">
	            <!-- wp:pattern {"slug":"unluckytech/invoice"} /-->
            </div>

        </div>



</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarLinks = document.querySelectorAll('.account-sidebar ul li a');
        const sections = document.querySelectorAll('.account-section');
        const sidebar = document.querySelector('.account-sidebar');
        const overlay = document.createElement('div');
        
        // Create the overlay element and append it to the body
        overlay.classList.add('overlay');
        document.body.appendChild(overlay);

        // Toggle section on sidebar link click
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                // Check if the link is the client portal link
                if (link.href.includes('client_portal')) {
                    // Do nothing, let the link navigate normally
                    return;
                }

                event.preventDefault();
                sections.forEach(section => section.style.display = 'none'); // Hide all sections
                document.getElementById(link.dataset.section).style.display = 'block'; // Show the selected section

                // Hide sidebar and overlay when a section is selected
                sidebar.classList.remove('sidebar-active');
                overlay.classList.remove('overlay-active');
            });
        });

        // Change email form submission
        const changeEmailForm = document.getElementById('changeEmailForm');
        changeEmailForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const emailInput = document.getElementById('new_email').value;
            const verificationDiv = document.getElementById('email-verification');

            // Send AJAX request to change email
            fetch('<?php echo esc_url($_SERVER['REQUEST_URI']); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    change_email: '1',
                    new_email: emailInput
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // Show response message
                    if (data.success) {
                        verificationDiv.style.display = 'block'; // Show verification code input
                        document.getElementById('hidden_new_email').value = emailInput; // Set hidden new email field
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Email verification form submission
        const verificationForm = document.getElementById('verificationForm');
        verificationForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const verificationCode = document.getElementById('verification_code').value;
            const newEmail = document.getElementById('hidden_new_email').value;

            // Send AJAX request to verify email
            fetch('<?php echo esc_url($_SERVER['REQUEST_URI']); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    verification_code: verificationCode,
                    new_email: newEmail
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // Show response message
                    if (data.success) {
                        verificationForm.reset(); // Clear the form
                        verificationDiv.style.display = 'none'; // Hide verification section
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Change password form submission
        const changePasswordForm = document.getElementById('changePasswordForm');
        changePasswordForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                alert('New passwords do not match');
                return;
            }

            // Send AJAX request to change password
            fetch('<?php echo esc_url($_SERVER['REQUEST_URI']); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    change_password: '1',
                    current_password: currentPassword,
                    new_password: newPassword
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // Show response message
                    if (data.success) {
                        changePasswordForm.reset(); // Reset form on success
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Toggle password visibility
        document.getElementById('toggleCurrentPassword').addEventListener('change', function() {
            const currentPassword = document.getElementById('current_password');
            currentPassword.type = this.checked ? 'text' : 'password';
        });

        document.getElementById('toggleNewPassword').addEventListener('change', function() {
            const newPassword = document.getElementById('new_password');
            newPassword.type = this.checked ? 'text' : 'password';
        });

        // Sidebar toggle and overlay handling
        const settingsButton = document.getElementById('settingsButton');

        // Toggle sidebar and overlay on button click
        settingsButton.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar-active');
            overlay.classList.toggle('overlay-active');
        });

        // Hide sidebar and overlay when the overlay is clicked
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('sidebar-active');
            overlay.classList.remove('overlay-active');
        });
    });
</script>