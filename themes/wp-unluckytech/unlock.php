<?php
/**
 * One-time admin unlock script.
 * Visit once, then DELETE from server immediately.
 * URL: https://unluckytech.com/wp-content/themes/wp-unluckytech/unlock.php
 */

define('ABSPATH', dirname(__FILE__) . '/../../../../');
define('WPINC', 'wp-includes');
require_once ABSPATH . 'wp-load.php';

$ip      = $_SERVER['REMOTE_ADDR'];
$ip_hash = md5($ip);

// Clear IP-level blocks
delete_transient('ut_ip_block_' . $ip_hash);
delete_transient('ut_ip_count_' . $ip_hash);

// Clear user-level lockout for every admin account
$admins = get_users(['role' => 'administrator']);
$cleared = [];
foreach ($admins as $admin) {
    delete_user_meta($admin->ID, '_failed_login_attempts');
    delete_user_meta($admin->ID, '_last_failed_login');
    $cleared[] = esc_html($admin->user_login);
}

echo '<p>IP <strong>' . esc_html($ip) . '</strong> unblocked.</p>';
echo '<p>Failed attempt counters reset for: <strong>' . implode(', ', $cleared) . '</strong></p>';
echo '<p style="color:red;font-weight:bold;">Delete this file from the server now.</p>';
echo '<p><a href="/wp-login.php">Go to login</a></p>';
