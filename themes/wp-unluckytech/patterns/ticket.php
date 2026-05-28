<?php
/**
 * Title: Ticket Status
 * Slug: unluckytech/ticket
 * Categories: text, featured
 * Description: Check the status of a support ticket and send replies.
 */

$ticket_info    = null;
$ticket_replies = [];
$lookup_error   = '';
$reply_message  = '';

// ── Handle user reply ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ut_ticket_reply'])) {
    if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'ut_ticket_reply')) {
        $reply_message = 'error:Security check failed.';
    } elseif (class_exists('UT_Ticket_Handler')) {
        $r_number  = sanitize_text_field($_POST['ticket_number'] ?? '');
        $r_email   = sanitize_email($_POST['reply_email'] ?? '');
        $r_message = sanitize_textarea_field($_POST['reply_message_text'] ?? '');
        $handler   = new UT_Ticket_Handler();

        $found = is_user_logged_in()
            ? $handler->get_ticket($r_number)
            : $handler->get_ticket($r_number, $r_email);

        if ($found && is_user_logged_in() && (int)$found->user_id !== get_current_user_id()) {
            $found = null; // block viewing another user's ticket
        }

        if ($found && $r_message) {
            $uid = is_user_logged_in() ? get_current_user_id() : 0;
            $ok  = $handler->add_reply($found->id, 'user', $uid, $r_message);

            if ($ok) {
                // Notify admin
                $site       = get_bloginfo('name');
                $admin_body = '<p>New user reply on ticket <strong>#' . esc_html($found->ticket_number) . '</strong>:</p>'
                            . '<blockquote style="border-left:3px solid #ccc;margin:0;padding:0 1em;">' . nl2br(esc_html($r_message)) . '</blockquote>'
                            . '<p><a href="' . esc_url(admin_url('admin.php?page=ut-tickets&ticket=' . $found->id)) . '">View in admin</a></p>';
                wp_mail(get_option('admin_email'), '[' . $site . '] User reply on Ticket #' . $found->ticket_number, $admin_body, ['Content-Type: text/html; charset=UTF-8']);

                $reply_message = 'success:Your reply has been sent!';
                // Reload ticket for updated thread
                $ticket_info    = $handler->get_ticket_by_id($found->id);
                $ticket_replies = $handler->get_replies($found->id);
            } else {
                $reply_message = 'error:Failed to send reply. Please try again.';
                $ticket_info   = $found;
                $ticket_replies = $handler->get_replies($found->id);
            }
        } else {
            $reply_message = 'error:Could not verify your ticket. Please check the ticket number and email.';
        }
    }
}

// ── Handle ticket lookup ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_lookup'])) {
    if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'ut_ticket_lookup')) {
        $lookup_error = 'Security check failed. Please try again.';
    } elseif (class_exists('UT_Ticket_Handler')) {
        $lookup_number = sanitize_text_field($_POST['ticket_number'] ?? '');
        $lookup_email  = sanitize_email($_POST['lookup_email'] ?? '');
        $handler       = new UT_Ticket_Handler();

        $found = is_user_logged_in()
            ? $handler->get_ticket($lookup_number)
            : $handler->get_ticket($lookup_number, $lookup_email);

        if ($found && is_user_logged_in() && (int)$found->user_id !== get_current_user_id()) {
            $found = null;
        }

        if ($found) {
            $ticket_info    = $found;
            $ticket_replies = $handler->get_replies($found->id);
        } else {
            $lookup_error = 'No ticket found. Please check the ticket number' . (is_user_logged_in() ? '.' : ' and email.');
        }
    } else {
        $lookup_error = 'The ticketing system is currently unavailable.';
    }
}

// Status label helper
function ut_status_label($status) {
    return match($status) {
        'open'    => 'Open',
        'pending' => 'Pending',
        'closed'  => 'Closed',
        default   => ucfirst($status),
    };
}

$reply_nonce  = wp_create_nonce('ut_ticket_reply');
$lookup_nonce = wp_create_nonce('ut_ticket_lookup');
?>

<div id="ticket-status" class="tab">

    <?php if (!$ticket_info): ?>
    <!-- ── Lookup form ── -->
    <div class="ut-lookup-section">
        <h2>Check Ticket Status</h2>
        <p>Enter your ticket number<?php echo is_user_logged_in() ? '' : ' and the email you used to submit it'; ?> to view your ticket and its replies.</p>

        <?php if ($lookup_error): ?>
            <div class="contact-error"><p><?php echo esc_html($lookup_error); ?></p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(get_permalink()); ?>" class="contact-form ut-lookup-form">
            <input type="hidden" name="ticket_lookup" value="1">
            <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($lookup_nonce); ?>">

            <div class="contact-group">
                <label for="ticket_number">Ticket Number</label>
                <input type="text" id="ticket_number" name="ticket_number"
                       placeholder="UT-XXXXXX" pattern="UT-[A-Z0-9]{6}" required
                       value="<?php echo esc_attr($_POST['ticket_number'] ?? ''); ?>">
            </div>

            <?php if (!is_user_logged_in()): ?>
            <div class="contact-group">
                <label for="lookup_email">Email Address</label>
                <input type="email" id="lookup_email" name="lookup_email" required
                       value="<?php echo esc_attr($_POST['lookup_email'] ?? ''); ?>">
            </div>
            <?php endif; ?>

            <button type="submit" class="submit-button">Look Up Ticket</button>
        </form>
    </div>

    <?php else: ?>
    <!-- ── Ticket detail ── -->
    <?php
        $reply_parts   = explode(':', $reply_message, 2);
        $reply_status  = $reply_parts[0] ?? '';
        $reply_text    = $reply_parts[1] ?? '';
    ?>

    <div class="ut-ticket-detail">
        <div class="ut-ticket-detail-header">
            <div>
                <h2><?php echo esc_html($ticket_info->subject); ?></h2>
                <p class="ut-ticket-meta-line">
                    Ticket <strong>#<?php echo esc_html($ticket_info->ticket_number); ?></strong>
                    &bull; <?php echo esc_html($ticket_info->service); ?>
                    &bull; <?php echo esc_html($ticket_info->inquiry_type); ?>
                    &bull; Opened <?php echo esc_html(date_i18n('M j, Y', strtotime($ticket_info->created_at))); ?>
                </p>
            </div>
            <span class="ut-status-badge ut-status-<?php echo esc_attr($ticket_info->status); ?>">
                <?php echo esc_html(ut_status_label($ticket_info->status)); ?>
            </span>
        </div>

        <?php if ($reply_status === 'success'): ?>
            <div class="contact-success"><p><?php echo esc_html($reply_text); ?></p></div>
        <?php elseif ($reply_status === 'error'): ?>
            <div class="contact-error"><p><?php echo esc_html($reply_text); ?></p></div>
        <?php endif; ?>

        <!-- Thread -->
        <div class="ut-thread">

            <!-- Original message -->
            <div class="ut-thread-message ut-thread-user">
                <div class="ut-thread-meta">
                    <strong><?php echo esc_html($ticket_info->name); ?></strong>
                    <span><?php echo esc_html(date_i18n('M j, Y g:i a', strtotime($ticket_info->created_at))); ?></span>
                </div>
                <div class="ut-thread-body">
                    <?php echo nl2br(esc_html($ticket_info->message)); ?>
                </div>
            </div>

            <?php foreach ($ticket_replies as $reply): ?>
            <div class="ut-thread-message ut-thread-<?php echo esc_attr($reply->author_type); ?>">
                <div class="ut-thread-meta">
                    <strong>
                        <?php echo $reply->author_type === 'admin'
                            ? esc_html(get_bloginfo('name')) . ' <span class="ut-staff-tag">Staff</span>'
                            : esc_html($ticket_info->name); ?>
                    </strong>
                    <span><?php echo esc_html(date_i18n('M j, Y g:i a', strtotime($reply->created_at))); ?></span>
                </div>
                <div class="ut-thread-body">
                    <?php echo nl2br(esc_html($reply->message)); ?>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <!-- Reply form (hide if closed) -->
        <?php if ($ticket_info->status !== 'closed'): ?>
        <div class="ut-reply-form-wrap">
            <h3>Send a Reply</h3>
            <form method="post" action="<?php echo esc_url(get_permalink()); ?>" class="contact-form">
                <input type="hidden" name="ut_ticket_reply" value="1">
                <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($reply_nonce); ?>">
                <input type="hidden" name="ticket_number" value="<?php echo esc_attr($ticket_info->ticket_number); ?>">

                <?php if (!is_user_logged_in()): ?>
                <div class="contact-group">
                    <label for="reply_email">Your Email</label>
                    <input type="email" id="reply_email" name="reply_email" required>
                </div>
                <?php endif; ?>

                <div class="contact-group">
                    <label for="reply_message_text">Message</label>
                    <textarea id="reply_message_text" name="reply_message_text" rows="5" required></textarea>
                </div>

                <button type="submit" class="submit-button">Send Reply</button>
            </form>
        </div>
        <?php else: ?>
        <p class="ut-ticket-closed-note">This ticket is closed. <a href="<?php echo esc_url(get_permalink()); ?>">Open a new lookup</a> or submit a new message via the contact form.</p>
        <?php endif; ?>

        <p class="ut-lookup-again"><a href="<?php echo esc_url(get_permalink()); ?>">&larr; Look up a different ticket</a></p>
    </div>

    <?php endif; ?>

</div>
