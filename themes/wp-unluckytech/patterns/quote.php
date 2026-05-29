<?php
/**
 * Title: Quotes
 * Slug: unluckytech/quote
 * Categories: text, featured
 * Description: This file manages and displays user quotes with download functionality.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/invoiceninja.php';

$current_user = wp_get_current_user();

if ($current_user->ID == 0) {
    echo '<p>You must be logged in to view your quotes.</p>';
    return;
}

if (!ut_invoiceninja_available()) {
    echo '<p>Quotes are temporarily unavailable. Please check back later.</p>';
    return;
}

$client = ut_invoiceninja_find_client($current_user->user_email);

if ($client === null) {
    echo '<p>No quotes are associated with your account yet.</p>';
    return;
}

$quotes = ut_invoiceninja_get_documents('quotes', $client->id);

if ($quotes === null) {
    echo '<p>We couldn\'t load your quotes right now. Please try again later.</p>';
    return;
}

if (empty($quotes)) {
    echo '<p>No quotes found for your account.</p>';
    return;
}
?>

<h2 class="acc-title">Quotes</h2>
<div class="quote-container">
    <div class="table-container">
        <table class="quote-table">
            <thead>
                <tr>
                    <th>Quote #</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quotes as $quote): ?>
                    <tr>
                        <td><?php echo esc_html($quote->number); ?></td>
                        <td><?php echo esc_html(number_format((float) $quote->amount, 2)); ?></td>
                        <td><?php echo esc_html($quote->due_date); ?></td>
                        <td>
                            <?php $link = ut_invoiceninja_document_link('quote', $quote); ?>
                            <?php if ($link): ?>
                                <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener" class="view-button">View</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
