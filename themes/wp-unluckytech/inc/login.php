<?php

if (!function_exists('unluckytech_get_user_ip')) {
    function unluckytech_get_user_ip() {
        $keys = array('HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_X_CLUSTER_CLIENT_IP','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR');
        foreach ($keys as $k) {
            if (!empty($_SERVER[$k])) {
                $ip = $_SERVER[$k];
                // If multiple ips, take first
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                return $ip;
            }
        }
        return '0.0.0.0';
    }
}

if (!function_exists('unluckytech_ip_allowed')) {
    function unluckytech_ip_allowed($ip) {
        if (defined('UNLUCKYTECH_ALLOW_IPS')) {
            $allow = @unserialize(UNLUCKYTECH_ALLOW_IPS);
            if (is_array($allow) && in_array($ip, $allow, true)) {
                return true;
            }
        }
        return false;
    }
}

function unluckytech_block_unverified_users($user, $password) {
    if (!is_wp_error($user) && !current_user_can('administrator', $user->ID)) {
        $email_verified = get_user_meta($user->ID, 'email_verified', true);

        if ($email_verified != 1) {
            return new WP_Error('email_not_verified', __('ERROR: Your email is not verified. Please check your inbox for the verification link.'));
        }
    }

    return $user;
}
add_filter('wp_authenticate_user', 'unluckytech_block_unverified_users', 10, 2);

add_filter('authenticate', 'unluckytech_verify_captcha_and_ip', 5, 3);
function unluckytech_verify_captcha_and_ip($user, $username, $password) {
    $ip = unluckytech_get_user_ip();

    if (unluckytech_ip_allowed($ip)) {
        return $user;
    }

    $ip_key = 'ut_ip_block_' . md5($ip);
    if (get_transient($ip_key)) {
        return new WP_Error('too_many_attempts', __('Too many login attempts from your IP. Please try later.'));
    }

    if (defined('UNLUCKYTECH_RECAPTCHA_SECRET') && !empty(UNLUCKYTECH_RECAPTCHA_SECRET)) {
        if (empty($_POST['g-recaptcha-response'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return new WP_Error('captcha_missing', __('Please complete the CAPTCHA to continue.'));
            } else {
                return $user;
            }
        }

        $resp = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => UNLUCKYTECH_RECAPTCHA_SECRET,
                'response' => sanitize_text_field($_POST['g-recaptcha-response']),
                'remoteip' => $ip,
            ),
            'timeout' => 5,
        ));

        if (is_wp_error($resp)) {
            return $user;
        }

        $body = wp_remote_retrieve_body($resp);
        $json = json_decode($body, true);

        if (empty($json['success']) || $json['success'] !== true) {
            return new WP_Error('captcha_invalid', __('CAPTCHA verification failed. Please try again.'));
        }
    }

    return $user;
}

add_action('wp_login_failed', 'unluckytech_track_failed_logins', 10, 1);
function unluckytech_track_failed_logins($username) {
    $ip = unluckytech_get_user_ip();

    $ip_count_key = 'ut_ip_count_' . md5($ip);
    $ip_count = get_transient($ip_count_key);
    $ip_count = ($ip_count) ? intval($ip_count) + 1 : 1;
    set_transient($ip_count_key, $ip_count, HOUR_IN_SECONDS);

    $ip_threshold = 15; 
    if ($ip_count >= $ip_threshold) {
        set_transient('ut_ip_block_' . md5($ip), true, 15 * MINUTE_IN_SECONDS);
    }

    $user = get_user_by('login', $username);
    if ($user) {
        $user_id = $user->ID;
        $attempts = get_user_meta($user_id, '_failed_login_attempts', true);
        $attempts = (empty($attempts)) ? 0 : intval($attempts);
        $attempts++;
        update_user_meta($user_id, '_failed_login_attempts', $attempts);
        update_user_meta($user_id, '_last_failed_login', time());
    }
}

add_filter('wp_authenticate_user', 'custom_check_login_attempts', 99, 2);
function custom_check_login_attempts($user, $password) {
    if (is_wp_error($user)) {
        return $user; // Return error if user is invalid
    }

    if (current_user_can('administrator', $user->ID)) {
        return $user;
    }

    $user_id = $user->ID;

    $attempts = intval(get_user_meta($user_id, '_failed_login_attempts', true));
    $last_attempt = get_user_meta($user_id, '_last_failed_login', true);

    $lockout_time = custom_calculate_lockout_time($attempts);

    if (!empty($last_attempt) && (time() - $last_attempt) < $lockout_time) {
        $remaining_time = $lockout_time - (time() - $last_attempt);
        return new WP_Error('too_many_attempts', "Too many login attempts. Please try again in " . human_time_diff(time(), time() + $remaining_time) . ".");
    }

    return $user;
}

add_action('wp_login', 'unluckytech_clear_failed_counts_on_success', 10, 2);
function unluckytech_clear_failed_counts_on_success($user_login, $user) {
    delete_user_meta($user->ID, '_failed_login_attempts');
    delete_user_meta($user->ID, '_last_failed_login');

    $ip = unluckytech_get_user_ip();
    delete_transient('ut_ip_count_' . md5($ip));
    delete_transient('ut_ip_block_' . md5($ip));
}

function custom_calculate_lockout_time($attempts) {
    $timeout = 0;

    if ($attempts >= 5 && $attempts < 10) {
        $timeout = 2 * MINUTE_IN_SECONDS; // 2 minutes
    } elseif ($attempts >= 10 && $attempts < 15) {
        $timeout = 10 * MINUTE_IN_SECONDS; // 10 minutes
    } elseif ($attempts >= 15 && $attempts < 20) {
        $timeout = HOUR_IN_SECONDS; // 1 hour
    } elseif ($attempts >= 20 && $attempts < 25) {
        $timeout = 12 * HOUR_IN_SECONDS; // 12 hours
    } elseif ($attempts >= 25) {
        $timeout = DAY_IN_SECONDS; // 24 hours
    }

    return $timeout;
}

function disable_admin_bar_on_login($user_login, $user) {
    if (!in_array('administrator', (array) $user->roles)) {
        setcookie('disable_admin_bar', '1', time() + 3600 * 24 * 30, '/'); // expires in 30 days
    }
}
add_action('wp_login', 'disable_admin_bar_on_login', 10, 2);