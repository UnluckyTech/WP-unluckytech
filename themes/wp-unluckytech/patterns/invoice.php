<?php
/**
 * Title: Invoices
 * Slug: unluckytech/invoice
 * Categories: text, featured
 * Description: This file manages and displays user invoices.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/invoiceninja.php';

$current_user = wp_get_current_user();

if ($current_user->ID == 0) {
    echo '<p>You must be logged in to view your invoices.</p>';
    return;
}

if (!ut_invoiceninja_available()) {
    echo '<p>Invoices are temporarily unavailable. Please check back later.</p>';
    return;
}

$client = ut_invoiceninja_find_client($current_user->user_email);

if ($client === null) {
    echo '<p>No invoices are associated with your account yet.</p>';
    return;
}

$invoices = ut_invoiceninja_get_documents('invoices', $client->id);

if ($invoices === null) {
    echo '<p>We couldn\'t load your invoices right now. Please try again later.</p>';
    return;
}

if (empty($invoices)) {
    echo '<p>No invoices found for your account.</p>';
    return;
}
?>

<h1 class="acc-title">Your Invoices</h1>
<div class="table-container">
    <table class="invoice-table">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?php echo esc_html($invoice->number); ?></td>
                    <td><?php echo esc_html(number_format((float) $invoice->amount, 2)); ?></td>
                    <td><?php echo esc_html($invoice->due_date); ?></td>
                    <td>
                        <?php $link = ut_invoiceninja_document_link('invoice', $invoice); ?>
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
