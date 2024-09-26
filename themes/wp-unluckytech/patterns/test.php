<?php
/**
 * Title: Invoices
 * Slug: unluckytech/test
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
use InvoiceNinja\Api\BaseApi; // Assuming BaseApi is the correct class to use

// Check if the BaseApi class exists
if (class_exists('InvoiceNinja\Api\BaseApi')) {
    // Fetch the client information using the current user's email
    $client_response = BaseApi::sendRequest("clients?email={$current_user->user_email}");
    error_log('Client Response: ' . print_r($client_response, true)); // Debugging statement
    $client = json_decode($client_response);

    // Check if the client was found
    if (empty($client->data)) {
        echo '<p>Error: No client found for the current user in Invoice Ninja.</p>';
        return;
    }

    // Get the client ID
    $client_id = $client->data[0]->id;

    // Fetch the invoices for the client
    $invoices_response = BaseApi::sendRequest("invoices?client_id=$client_id&include=invitations");
    error_log('Invoices Response: ' . print_r($invoices_response, true)); // Debugging statement

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
        echo '<h1>Invoices</h1>';
        ?>
        <table>
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($invoice->number); ?></td>
                        <td><?php echo htmlspecialchars($invoice->amount); ?></td>
                        <td>
                            <?php 
                            // Map the status ID to human-readable status
                            $status_map = [
                                '1' => 'Draft',
                                '2' => 'Sent',
                                '3' => 'Viewed',
                                '4' => 'Approved',
                                '5' => 'Partial',
                                '6' => 'Paid',
                            ];
                            echo htmlspecialchars($status_map[$invoice->status_id] ?? 'Unknown');
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($invoice->due_date); ?></td>
                        <td>
                            <?php
                            // Construct the URL to download the invoice PDF
                            $pdf_url = 'https://invoice.unluckytech.com/api/v1/invoices/' . $invoice->id . '/pdf';
                            ?>
                            <a href="<?php echo esc_url($pdf_url); ?>" target="_blank">Download PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
} else {
    echo '<p>Error: Invoice Ninja API is not available.</p>';
}
?>
