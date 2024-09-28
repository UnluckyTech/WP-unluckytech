<?php
/**
 * Title: Quotes
 * Slug: unluckytech/quote
 * Categories: text, featured
 * Description: This file manages and displays user quotes with download functionality.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the current logged-in WordPress user
$current_user = wp_get_current_user();

// Check if the user is logged in
if ($current_user->ID == 0) {
    echo '<p>Error: You must be logged in to view your quotes.</p>';
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

    // Fetch quotes for the client using their ID
    $client_id = $client->id;
    $quotes_response = ClientApi::sendRequest("quotes?client_id=$client_id");

    if (!$quotes_response) {
        echo '<p>Error: Unable to fetch quotes for this client.</p>';
        return;
    }

    $quotes = json_decode($quotes_response)->data;

    // Check if there are any quotes
    if (empty($quotes)) {
        echo '<p>No quotes found for this user.</p>';
    } else {
        // Display the quotes in a table format
        echo '<h2 class="acc-title">Quotes</h2>';
        ?>
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
                                <td><?php echo htmlspecialchars($quote->number); ?></td>
                                <td><?php echo htmlspecialchars(number_format($quote->amount, 2)); ?></td>
                                <td><?php echo htmlspecialchars($quote->due_date); ?></td>
                                <td>
                                    <a href="https://invoice.unluckytech.com/client/quote/<?php echo htmlspecialchars($quote->invitations[0]->key); ?>" target="_blank" class="view-button">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    }
} else {
    echo '<p>Error: Invoice Ninja API is not available.</p>';
}
?>
