<?php
/*
Plugin Name: UnluckyTech Support Tickets
Description: Custom ticketing system — replaces Znuny/OTRS integration.
Version: 1.1.0
Author: UnluckyTech
*/

if (!defined('ABSPATH')) exit;

define('UT_TICKETS_VERSION', '1.1.0');
define('UT_TICKETS_PATH', plugin_dir_path(__FILE__));
define('UT_TICKETS_URL', plugin_dir_url(__FILE__));

require_once UT_TICKETS_PATH . 'includes/class-ticket-handler.php';

register_activation_hook(__FILE__, 'ut_tickets_create_tables');
function ut_tickets_create_tables() {
    global $wpdb;
    $c = $wpdb->get_charset_collate();
    $t = $wpdb->prefix . 'ut_tickets';
    $r = $wpdb->prefix . 'ut_ticket_replies';

    $sql = "CREATE TABLE $t (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        ticket_number varchar(20) NOT NULL,
        user_id bigint(20) NOT NULL DEFAULT 0,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(50) NOT NULL DEFAULT '',
        service varchar(100) NOT NULL,
        inquiry_type varchar(100) NOT NULL,
        subject varchar(255) NOT NULL,
        message longtext NOT NULL,
        status varchar(20) NOT NULL DEFAULT 'open',
        priority varchar(20) NOT NULL DEFAULT 'normal',
        created_at datetime NOT NULL,
        updated_at datetime NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY ticket_number (ticket_number),
        KEY user_id (user_id),
        KEY status (status)
    ) $c;

    CREATE TABLE $r (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        ticket_id bigint(20) NOT NULL,
        author_type varchar(10) NOT NULL DEFAULT 'user',
        author_id bigint(20) NOT NULL DEFAULT 0,
        message longtext NOT NULL,
        created_at datetime NOT NULL,
        PRIMARY KEY (id),
        KEY ticket_id (ticket_id)
    ) $c;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
    update_option('ut_tickets_db_version', UT_TICKETS_VERSION);
}

// ──────────────────────────────────────────────
// Admin menu
// ──────────────────────────────────────────────

add_action('admin_menu', 'ut_tickets_admin_menu');
function ut_tickets_admin_menu() {
    add_menu_page(
        'Support Tickets',
        'Support Tickets',
        'manage_options',
        'ut-tickets',
        'ut_tickets_admin_page',
        'dashicons-tickets-alt',
        25
    );
}

add_action('admin_enqueue_scripts', 'ut_tickets_admin_styles');
function ut_tickets_admin_styles($hook) {
    if (strpos($hook, 'ut-tickets') !== false) {
        wp_enqueue_style('ut-tickets-admin', UT_TICKETS_URL . 'assets/css/admin.css', [], UT_TICKETS_VERSION);
    }
}

// ──────────────────────────────────────────────
// Admin reply / update handler
// ──────────────────────────────────────────────

add_action('admin_post_ut_admin_reply', 'ut_handle_admin_reply');
function ut_handle_admin_reply() {
    if (!current_user_can('manage_options')) wp_die('Unauthorized.');
    if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'ut_admin_reply')) wp_die('Security check failed.');

    $ticket_id    = intval($_POST['ticket_id'] ?? 0);
    $message      = sanitize_textarea_field($_POST['reply_message'] ?? '');
    $new_status   = sanitize_text_field($_POST['ticket_status'] ?? '');
    $new_priority = sanitize_text_field($_POST['ticket_priority'] ?? '');

    if (!$ticket_id) {
        wp_redirect(admin_url('admin.php?page=ut-tickets'));
        exit;
    }

    $handler    = new UT_Ticket_Handler();
    $ticket     = $handler->get_ticket_by_id($ticket_id);
    $old_status = $ticket ? $ticket->status : '';

    // Add reply if message provided
    if ($message) {
        $handler->add_reply($ticket_id, 'admin', get_current_user_id(), $message);
    }

    // Update status if changed
    $status_changed = $new_status && $new_status !== $old_status;
    if ($status_changed) {
        $handler->update_status($ticket_id, $new_status);
    }

    // Update priority if provided
    if ($new_priority) {
        $handler->update_priority($ticket_id, $new_priority);
    }

    // Email notifications
    if ($ticket) {
        $site   = get_bloginfo('name');
        $labels = ['open' => 'Open', 'pending' => 'Pending', 'closed' => 'Closed'];

        // Reply notification
        if ($message) {
            $body  = '<p>Hello ' . esc_html($ticket->name) . ',</p>';
            $body .= '<p>Your ticket <strong>#' . esc_html($ticket->ticket_number) . '</strong> has a new reply:</p>';
            $body .= '<blockquote style="border-left:3px solid #ccc;margin:0;padding:0 1em;">' . nl2br(esc_html($message)) . '</blockquote>';
            if ($status_changed) {
                $body .= '<p>Ticket status updated to: <strong>' . esc_html($labels[$new_status] ?? ucfirst($new_status)) . '</strong>.</p>';
            }
            $body .= '<p>— ' . esc_html($site) . '</p>';
            wp_mail(
                $ticket->email,
                '[' . $site . '] Reply on Ticket #' . $ticket->ticket_number,
                $body,
                ['Content-Type: text/html; charset=UTF-8']
            );

        } elseif ($status_changed) {
            // Status-only notification (no reply message written)
            $label = $labels[$new_status] ?? ucfirst($new_status);
            $body  = '<p>Hello ' . esc_html($ticket->name) . ',</p>';
            $body .= '<p>Your ticket <strong>#' . esc_html($ticket->ticket_number) . '</strong> has been updated to <strong>' . esc_html($label) . '</strong>.</p>';
            if ($new_status === 'closed') {
                $body .= '<p>This ticket is now closed. If you need further help, please submit a new inquiry.</p>';
            }
            $body .= '<p>— ' . esc_html($site) . '</p>';
            wp_mail(
                $ticket->email,
                '[' . $site . '] Ticket #' . $ticket->ticket_number . ' — Status Updated',
                $body,
                ['Content-Type: text/html; charset=UTF-8']
            );
        }
    }

    $param = $message ? '&replied=1' : '&updated=1';
    wp_redirect(admin_url('admin.php?page=ut-tickets&ticket=' . $ticket_id . $param));
    exit;
}

// ──────────────────────────────────────────────
// Admin page
// ──────────────────────────────────────────────

function ut_tickets_admin_page() {
    $handler = new UT_Ticket_Handler();

    // ── Single ticket view ──────────────────────────────────────────────────
    if (isset($_GET['ticket'])) {
        $ticket_id = intval($_GET['ticket']);
        $ticket    = $handler->get_ticket_by_id($ticket_id);
        $replies   = $ticket ? $handler->get_replies($ticket_id) : [];

        if (!$ticket) {
            echo '<div class="wrap"><p>Ticket not found.</p></div>';
            return;
        }
        ?>
        <div class="wrap ut-ticket-wrap">
            <h1>
                <a href="<?php echo esc_url(admin_url('admin.php?page=ut-tickets')); ?>">Support Tickets</a>
                &rsaquo; #<?php echo esc_html($ticket->ticket_number); ?>
            </h1>

            <?php if (isset($_GET['replied'])): ?>
                <div class="notice notice-success is-dismissible"><p>Reply sent.</p></div>
            <?php elseif (isset($_GET['updated'])): ?>
                <div class="notice notice-success is-dismissible"><p>Ticket updated.</p></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="notice notice-error is-dismissible"><p>Nothing to save — provide a reply or change the status/priority.</p></div>
            <?php endif; ?>

            <div class="ut-ticket-layout">

                <div class="ut-ticket-main">
                    <!-- Original message -->
                    <div class="ut-ticket-card">
                        <h2><?php echo esc_html($ticket->subject); ?></h2>
                        <div class="ut-ticket-meta">
                            <span class="ut-badge ut-badge-<?php echo esc_attr($ticket->status); ?>">
                                <?php echo esc_html(ucfirst($ticket->status)); ?>
                            </span>
                            <span class="ut-badge ut-badge-priority-<?php echo esc_attr($ticket->priority); ?>">
                                <?php echo esc_html(ucfirst($ticket->priority)); ?>
                            </span>
                        </div>
                        <div class="ut-message">
                            <div class="ut-message-author">
                                <strong><?php echo esc_html($ticket->name); ?></strong>
                                <span><?php echo esc_html(date_i18n('M j, Y g:i a', strtotime($ticket->created_at))); ?></span>
                            </div>
                            <div class="ut-message-body">
                                <?php echo nl2br(esc_html($ticket->message)); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Reply thread -->
                    <?php foreach ($replies as $reply): ?>
                    <div class="ut-ticket-card ut-reply ut-reply-<?php echo esc_attr($reply->author_type); ?>">
                        <div class="ut-message">
                            <div class="ut-message-author">
                                <strong>
                                    <?php echo $reply->author_type === 'admin'
                                        ? esc_html(get_bloginfo('name')) . ' <span class="ut-staff-tag">Staff</span>'
                                        : esc_html($ticket->name); ?>
                                </strong>
                                <span><?php echo esc_html(date_i18n('M j, Y g:i a', strtotime($reply->created_at))); ?></span>
                            </div>
                            <div class="ut-message-body">
                                <?php echo nl2br(esc_html($reply->message)); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- Reply / update form -->
                    <div class="ut-ticket-card">
                        <h3>Reply &amp; Update</h3>
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <input type="hidden" name="action" value="ut_admin_reply">
                            <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                            <?php wp_nonce_field('ut_admin_reply'); ?>

                            <div class="ut-form-row">
                                <div class="ut-form-group">
                                    <label for="ticket_status">Status</label>
                                    <select id="ticket_status" name="ticket_status">
                                        <option value="">— No change —</option>
                                        <option value="open"    <?php selected($ticket->status, 'open'); ?>>Open</option>
                                        <option value="pending" <?php selected($ticket->status, 'pending'); ?>>Pending</option>
                                        <option value="closed"  <?php selected($ticket->status, 'closed'); ?>>Closed</option>
                                    </select>
                                </div>
                                <div class="ut-form-group">
                                    <label for="ticket_priority">Priority</label>
                                    <select id="ticket_priority" name="ticket_priority">
                                        <option value="">— No change —</option>
                                        <option value="low"    <?php selected($ticket->priority, 'low'); ?>>Low</option>
                                        <option value="normal" <?php selected($ticket->priority, 'normal'); ?>>Normal</option>
                                        <option value="high"   <?php selected($ticket->priority, 'high'); ?>>High</option>
                                    </select>
                                </div>
                            </div>

                            <div class="ut-form-group">
                                <label for="reply_message">
                                    Reply Message
                                    <span class="ut-optional">(optional — leave blank to only update status/priority)</span>
                                </label>
                                <textarea id="reply_message" name="reply_message" rows="6"
                                    placeholder="Type your reply… (leave empty to just update status or priority)"></textarea>
                            </div>

                            <button type="submit" class="button button-primary">Save</button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="ut-ticket-sidebar">
                    <div class="ut-ticket-card">
                        <h3>Ticket Info</h3>
                        <dl class="ut-info-list">
                            <dt>Ticket #</dt>
                            <dd><?php echo esc_html($ticket->ticket_number); ?></dd>
                            <dt>From</dt>
                            <dd><?php echo esc_html($ticket->name); ?></dd>
                            <dt>Email</dt>
                            <dd><a href="mailto:<?php echo esc_attr($ticket->email); ?>"><?php echo esc_html($ticket->email); ?></a></dd>
                            <?php if ($ticket->phone): ?>
                            <dt>Phone</dt>
                            <dd><?php echo esc_html($ticket->phone); ?></dd>
                            <?php endif; ?>
                            <dt>Service</dt>
                            <dd><?php echo esc_html($ticket->service); ?></dd>
                            <dt>Type</dt>
                            <dd><?php echo esc_html($ticket->inquiry_type); ?></dd>
                            <dt>Opened</dt>
                            <dd><?php echo esc_html(date_i18n('M j, Y', strtotime($ticket->created_at))); ?></dd>
                            <dt>Updated</dt>
                            <dd><?php echo esc_html(date_i18n('M j, Y', strtotime($ticket->updated_at))); ?></dd>
                        </dl>
                    </div>
                </div>

            </div>
        </div>
        <?php
        return;
    }

    // ── Ticket list ─────────────────────────────────────────────────────────
    $status_filter = sanitize_text_field($_GET['status'] ?? '');
    $search        = sanitize_text_field($_GET['search'] ?? '');
    $current_page  = max(1, intval($_GET['paged'] ?? 1));
    $per_page      = 20;

    $tickets     = $handler->get_all_tickets($status_filter, $search, $current_page, $per_page);
    $total       = $handler->count_tickets($status_filter, $search);
    $total_pages = (int) ceil($total / $per_page);
    $counts      = $handler->get_status_counts();
    $grand_total = array_sum($counts);
    ?>
    <div class="wrap ut-ticket-wrap">
        <h1>Support Tickets</h1>

        <ul class="subsubsub">
            <li>
                <a href="<?php echo esc_url(admin_url('admin.php?page=ut-tickets')); ?>"
                   <?php if (!$status_filter) echo 'class="current"'; ?>>
                    All <span class="count">(<?php echo $grand_total; ?>)</span>
                </a> |
            </li>
            <li>
                <a href="<?php echo esc_url(admin_url('admin.php?page=ut-tickets&status=open')); ?>"
                   <?php if ($status_filter === 'open') echo 'class="current"'; ?>>
                    Open <span class="count">(<?php echo $counts['open'] ?? 0; ?>)</span>
                </a> |
            </li>
            <li>
                <a href="<?php echo esc_url(admin_url('admin.php?page=ut-tickets&status=pending')); ?>"
                   <?php if ($status_filter === 'pending') echo 'class="current"'; ?>>
                    Pending <span class="count">(<?php echo $counts['pending'] ?? 0; ?>)</span>
                </a> |
            </li>
            <li>
                <a href="<?php echo esc_url(admin_url('admin.php?page=ut-tickets&status=closed')); ?>"
                   <?php if ($status_filter === 'closed') echo 'class="current"'; ?>>
                    Closed <span class="count">(<?php echo $counts['closed'] ?? 0; ?>)</span>
                </a>
            </li>
        </ul>

        <!-- Search bar -->
        <form method="get" class="ut-search-form">
            <input type="hidden" name="page" value="ut-tickets">
            <?php if ($status_filter): ?>
                <input type="hidden" name="status" value="<?php echo esc_attr($status_filter); ?>">
            <?php endif; ?>
            <input type="search" name="search" value="<?php echo esc_attr($search); ?>"
                   placeholder="Search by name, email, or ticket #…" class="ut-search-input">
            <button type="submit" class="button">Search</button>
            <?php if ($search): ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=ut-tickets' . ($status_filter ? '&status=' . $status_filter : ''))); ?>"
                   class="button">Clear</a>
            <?php endif; ?>
        </form>

        <?php if ($search): ?>
            <p class="ut-search-result-note">
                Showing <?php echo $total; ?> result<?php echo $total !== 1 ? 's' : ''; ?> for
                &ldquo;<?php echo esc_html($search); ?>&rdquo;
            </p>
        <?php endif; ?>

        <table class="wp-list-table widefat fixed striped" style="margin-top:8px;">
            <thead>
                <tr>
                    <th style="width:110px;">Ticket #</th>
                    <th>Subject</th>
                    <th>From</th>
                    <th>Service</th>
                    <th style="width:90px;">Status</th>
                    <th style="width:80px;">Priority</th>
                    <th style="width:100px;">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="7" style="padding:20px;text-align:center;color:#666;">
                        <?php echo $search ? 'No tickets match your search.' : 'No tickets found.'; ?>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                <tr>
                    <td>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=ut-tickets&ticket=' . $t->id)); ?>">
                            <?php echo esc_html($t->ticket_number); ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=ut-tickets&ticket=' . $t->id)); ?>">
                            <?php echo esc_html($t->subject); ?>
                        </a>
                    </td>
                    <td>
                        <?php echo esc_html($t->name); ?><br>
                        <small style="color:#666;"><?php echo esc_html($t->email); ?></small>
                    </td>
                    <td><?php echo esc_html($t->service); ?></td>
                    <td><span class="ut-badge ut-badge-<?php echo esc_attr($t->status); ?>"><?php echo esc_html(ucfirst($t->status)); ?></span></td>
                    <td><span class="ut-badge ut-badge-priority-<?php echo esc_attr($t->priority); ?>"><?php echo esc_html(ucfirst($t->priority)); ?></span></td>
                    <td><?php echo esc_html(date_i18n('M j, Y', strtotime($t->created_at))); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo $total; ?> item<?php echo $total !== 1 ? 's' : ''; ?></span>
                <span class="pagination-links">
                    <?php
                    $base_url = admin_url('admin.php?page=ut-tickets'
                        . ($status_filter ? '&status=' . $status_filter : '')
                        . ($search ? '&search=' . urlencode($search) : ''));

                    if ($current_page > 1):
                        echo '<a class="prev-page button" href="' . esc_url($base_url . '&paged=' . ($current_page - 1)) . '">&laquo;</a> ';
                    endif;

                    echo '<span class="paging-input">' . $current_page . ' of ' . $total_pages . '</span>';

                    if ($current_page < $total_pages):
                        echo ' <a class="next-page button" href="' . esc_url($base_url . '&paged=' . ($current_page + 1)) . '">&raquo;</a>';
                    endif;
                    ?>
                </span>
            </div>
        </div>
        <?php endif; ?>

    </div>
    <?php
}
