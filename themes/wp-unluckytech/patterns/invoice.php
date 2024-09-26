<?php
/**
 * Title: Invoices
 * Slug: unluckytech/invoice
 * Categories: text, featured
 * Description: This file manages and displays user invoices.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the current logged-in WordPress user
$current_user = wp_get_current_user();

// Check if the user is logged in
if ($current_user->ID == 0) {
    echo '<p>Error: You must be logged in to view your invoices.</p>';
    return;
}

// Include the necessary Invoice Ninja API class
use InvoiceNinja\Api\ClientApi;

// Check if the ClientApi class exists
if (class_exists('InvoiceNinja\Api\ClientApi')) {

    // Find the client in Invoice Ninja using the current user's email
    $client = ClientApi::find($current_user->user_email);

    // Check if the client was found
    if ($client === null) {
        echo '<p>Error: No client found for the current user in Invoice Ninja.</p>';
        return;
    }

    // Assuming you have a method to fetch invoices for a client
    $client_id = $client->id;

    // Add ?include=invitations to include the invitation key
    $invoices_response = ClientApi::sendRequest("invoices?client_id=$client_id&include=invitations");

    if (!$invoices_response) {
        echo '<p>Error: Unable to fetch invoices for this client.</p>';
        return;
    }

    $invoices = json_decode($invoices_response)->data;

    // Check if there are any invoices
    if (empty($invoices)) {
        echo '<p>No invoices found for this user.</p>';
    } else {
        // Display the invoices in a table format
        echo '<h1 class="invoice-title">Your Invoices</h1>';
        ?>
        <div class="invoice-container">
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
                            <td><?php echo htmlspecialchars($invoice->number); ?></td>
                            <td><?php echo htmlspecialchars(number_format($invoice->amount, 2)); ?></td>
                            <td><?php echo htmlspecialchars($invoice->due_date); ?></td>
                            <td>
                                <?php
                                // View invoice button
                                if (!empty($invoice->invitations[0]->key)) {
                                    $view_url = "https://invoice.unluckytech.com/client/invoice/{$invoice->invitations[0]->key}";
                                    echo '<a href="' . esc_url($view_url) . '" target="_blank" class="view-button">View</a>';
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
} else {
    echo '<p>Error: Invoice Ninja API is not available.</p>';
}
?>
