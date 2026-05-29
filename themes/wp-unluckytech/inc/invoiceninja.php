<?php
/**
 * Self-contained Invoice Ninja REST client for the theme.
 *
 * Reads the same WordPress options the Invoice Ninja plugin stores
 * (invoiceninja_api_token / invoiceninja_api_url) and talks to the versioned
 * /api/v1/ REST API directly. This means the theme does NOT depend on the
 * plugin's internal PHP classes (ClientApi, BaseApi, …), which change between
 * plugin releases and have historically broken the account page on update.
 *
 * Every function fails quietly (returns null) — it never echoes or exits, so a
 * misconfigured token or an API hiccup degrades gracefully instead of taking
 * the whole page down.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('ut_invoiceninja_available')) {
    /** Is the integration configured (token present)? */
    function ut_invoiceninja_available() {
        return (bool) get_option('invoiceninja_api_token');
    }
}

if (!function_exists('ut_invoiceninja_api_base')) {
    /** Normalised API base, always ending in /api/v1/. Mirrors the plugin's logic. */
    function ut_invoiceninja_api_base() {
        $url = get_option('invoiceninja_api_url');
        if (empty($url)) {
            return 'https://invoicing.co/api/v1/';
        }
        $url = rtrim($url, '/');
        $url = rtrim($url, 'api/v1');
        $url = rtrim($url, '/');
        return $url . '/api/v1/';
    }
}

if (!function_exists('ut_invoiceninja_portal_base')) {
    /** Client-portal base URL (no /api/v1), for building invoice/quote view links. */
    function ut_invoiceninja_portal_base() {
        $url = get_option('invoiceninja_api_url');
        if (empty($url)) {
            return 'https://invoice.unluckytech.com';
        }
        $url = rtrim($url, '/');
        $url = rtrim($url, 'api/v1');
        $url = rtrim($url, '/');
        return $url !== '' ? $url : 'https://invoice.unluckytech.com';
    }
}

if (!function_exists('ut_invoiceninja_request')) {
    /**
     * GET a route from the Invoice Ninja API.
     *
     * @param string $route e.g. "clients?email=foo@bar.com"
     * @return object|null Decoded JSON body on HTTP 200, otherwise null.
     */
    function ut_invoiceninja_request($route) {
        $token = get_option('invoiceninja_api_token');
        if (empty($token)) {
            return null;
        }

        $url      = ut_invoiceninja_api_base() . ltrim($route, '/');
        $response = wp_remote_get($url, [
            'timeout' => 20,
            'headers' => [
                'X-API-TOKEN'       => $token,
                'X-CLIENT-PLATFORM' => 'WordPress',
                'Content-Type'      => 'application/json',
            ],
        ]);

        if (is_wp_error($response)) {
            return null;
        }
        if ((int) wp_remote_retrieve_response_code($response) !== 200) {
            return null;
        }

        $body = json_decode(wp_remote_retrieve_body($response));
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        return $body;
    }
}

if (!function_exists('ut_invoiceninja_find_client')) {
    /**
     * Find a client by email.
     *
     * @return object|null Client object (with ->id, ->contacts, …) or null.
     */
    function ut_invoiceninja_find_client($email) {
        $body = ut_invoiceninja_request('clients?email=' . rawurlencode($email));
        if (!$body || empty($body->data) || !is_array($body->data)) {
            return null;
        }
        return $body->data[0];
    }
}

if (!function_exists('ut_invoiceninja_get_documents')) {
    /**
     * Fetch invoices or quotes for a client, with payment invitations included
     * (the invitation key is what builds the client-portal view link).
     *
     * @param string $type      'invoices' or 'quotes'
     * @param mixed  $client_id
     * @return array|null  Array of records, [] if none, or null on API failure.
     */
    function ut_invoiceninja_get_documents($type, $client_id) {
        $type = ($type === 'quotes') ? 'quotes' : 'invoices';
        $body = ut_invoiceninja_request($type . '?client_id=' . rawurlencode($client_id) . '&include=invitations');
        if (!$body || !isset($body->data)) {
            return null;
        }
        return is_array($body->data) ? $body->data : [];
    }
}

if (!function_exists('ut_invoiceninja_document_link')) {
    /**
     * Build a client-portal view link for an invoice/quote record.
     *
     * @param string $type 'invoice' or 'quote'
     * @return string|null URL or null if no invitation key is present.
     */
    function ut_invoiceninja_document_link($type, $record) {
        $type = ($type === 'quote') ? 'quote' : 'invoice';
        if (empty($record->invitations[0]->key)) {
            return null;
        }
        return ut_invoiceninja_portal_base() . '/client/' . $type . '/' . $record->invitations[0]->key;
    }
}
