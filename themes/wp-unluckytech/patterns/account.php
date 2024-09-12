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

// Handle password change request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = sanitize_text_field($_POST['current_password']);
    $new_password = sanitize_text_field($_POST['new_password']);

    if (wp_check_password($current_password, $current_user->user_pass, $current_user->ID)) {
        wp_set_password($new_password, $current_user->ID);
        echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    }

    exit; // Stop processing further
}
?>

<div class="account-container">
    <!-- Account Banner -->
    <div class="account-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="account-banner-overlay">
            <h1 class="account-title">Account</h1>
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
                <li><a href="#" data-section="notifications">Notifications</a></li>

                <!-- Security Section -->
                <li class="sidebar-section-title">Security</li>
                <li><a href="#" data-section="email-section">Email</a></li>
                <li><a href="#" data-section="password-section">Password</a></li>

                <!-- Purchase Section (placeholder for Billing and Invoices) -->
                <li class="sidebar-section-title">Billing & Purchase</li>
                <li><a href="#" data-section="billing-section">Billing</a></li>
                <li><a href="#" data-section="invoice-section">Invoices</a></li>
            </ul>
        </div>

        <div class="account-display">
            <!-- Profile Section -->
            <div class="account-section" id="profile-section" style="display: block;">
                <h2>Profile</h2>
                <?php if ($current_user->exists()) : ?>
                    <form id="profileForm" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <input type="hidden" name="action" value="save_profile">
                        <!-- Profile Picture -->
                        <?php $profile_picture = get_avatar(get_current_user_id()); ?>
                        <div class="profile-picture">
                            <?php echo $profile_picture ?: '<a href="https://en.gravatar.com/" target="_blank">Set your profile picture on Gravatar</a>'; ?>
                        </div>

                        <!-- Nickname -->
                        <p>
                            <label for="nickname">Nickname:</label><br>
                            <input type="text" id="nickname" name="nickname" value="<?php echo esc_attr($current_user->nickname); ?>" />
                        </p>

                        <!-- First Name -->
                        <p>
                            <label for="first_name">First Name:</label><br>
                            <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($current_user->user_firstname); ?>" />
                        </p>

                        <!-- Last Name -->
                        <p>
                            <label for="last_name">Last Name:</label><br>
                            <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($current_user->user_lastname); ?>" />
                        </p>

                        <p><button type="submit">Save Profile</button></p>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Notifications Section -->
            <div class="account-section" id="notifications-section" style="display: none;">
                <h2>Notifications</h2>
                <!-- Existing notifications content here -->
            </div>

            <!-- Email Section -->
            <div class="account-section" id="email-section" style="display: none;">
                <h2>Change Email</h2>
                    <!-- Change Email Form -->
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

            <!-- Password Section -->
            <div class="account-section" id="password-section" style="display: none;">
                <h2>Change Password</h2>
                <form id="changePasswordForm" method="post">
                    <!-- Current Password -->
                    <label for="current_password">Current Password:</label><br>
                    <input type="password" id="current_password" name="current_password" required />
                    <input type="checkbox" id="toggleCurrentPassword"> Show Password<br><br>

                    <!-- New Password -->
                    <label for="new_password">New Password:</label><br>
                    <input type="password" id="new_password" name="new_password" required />
                    <input type="checkbox" id="toggleNewPassword"> Show Password<br><br>

                    <!-- Confirm New Password -->
                    <label for="confirm_password">Confirm New Password:</label><br>
                    <input type="password" id="confirm_password" name="confirm_password" required /><br><br>

                    <button type="submit" name="change_password" class="test-email-button">Change Password</button>
                </form>
            </div>

            <!-- Billing Section -->
            <div class="account-section" id="billing-section" style="display: none;">
                <h2>Billing</h2>
                <p>This section is under construction.</p>
            </div>

            <!-- Invoices Section -->
            <div class="account-section" id="invoice-section" style="display: none;">
                <h2>Invoices</h2>
                <p>This section is under construction.</p>
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
