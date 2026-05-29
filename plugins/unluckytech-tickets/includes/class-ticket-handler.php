<?php

if (!defined('ABSPATH')) exit;

class UT_Ticket_Handler {

    private $tickets_table;
    private $replies_table;

    public function __construct() {
        global $wpdb;
        $this->tickets_table = $wpdb->prefix . 'ut_tickets';
        $this->replies_table = $wpdb->prefix . 'ut_ticket_replies';
    }

    public function create_ticket($data) {
        global $wpdb;

        $ticket_number = $this->generate_ticket_number();
        $now = current_time('mysql');

        $wpdb->insert(
            $this->tickets_table,
            [
                'ticket_number' => $ticket_number,
                'user_id'       => $data['user_id'] ?? 0,
                'name'          => $data['name'],
                'email'         => $data['email'],
                'phone'         => $data['phone'] ?? '',
                'service'       => $data['service'],
                'inquiry_type'  => $data['inquiry_type'],
                'subject'       => $data['subject'],
                'message'       => $data['message'],
                'status'        => 'open',
                'priority'      => $data['priority'] ?? 'normal',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            ['%s','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s']
        );

        return $wpdb->last_error ? false : $ticket_number;
    }

    public function get_ticket($ticket_number, $email = null) {
        global $wpdb;

        if ($email) {
            return $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$this->tickets_table} WHERE ticket_number = %s AND email = %s",
                $ticket_number,
                $email
            ));
        }

        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->tickets_table} WHERE ticket_number = %s",
            $ticket_number
        ));
    }

    public function get_ticket_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->tickets_table} WHERE id = %d",
            $id
        ));
    }

    public function get_user_tickets($user_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->tickets_table} WHERE user_id = %d ORDER BY updated_at DESC",
            $user_id
        ));
    }

    public function get_all_tickets($status = '', $search = '', $page = 1, $per_page = 20) {
        global $wpdb;

        $where  = [];
        $values = [];

        if ($status) {
            $where[]  = 'status = %s';
            $values[] = $status;
        }

        if ($search) {
            $like     = '%' . $wpdb->esc_like($search) . '%';
            $where[]  = '(ticket_number LIKE %s OR name LIKE %s OR email LIKE %s OR subject LIKE %s)';
            $values   = array_merge($values, [$like, $like, $like, $like]);
        }

        $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $offset    = max(0, ($page - 1)) * $per_page;

        $values[] = $per_page;
        $values[] = $offset;

        $sql = "SELECT * FROM {$this->tickets_table} {$where_sql} ORDER BY updated_at DESC LIMIT %d OFFSET %d";

        return $wpdb->get_results($wpdb->prepare($sql, ...$values));
    }

    public function count_tickets($status = '', $search = '') {
        global $wpdb;

        $where  = [];
        $values = [];

        if ($status) {
            $where[]  = 'status = %s';
            $values[] = $status;
        }

        if ($search) {
            $like    = '%' . $wpdb->esc_like($search) . '%';
            $where[] = '(ticket_number LIKE %s OR name LIKE %s OR email LIKE %s OR subject LIKE %s)';
            $values  = array_merge($values, [$like, $like, $like, $like]);
        }

        $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $sql       = "SELECT COUNT(*) FROM {$this->tickets_table} {$where_sql}";

        return intval($values ? $wpdb->get_var($wpdb->prepare($sql, ...$values)) : $wpdb->get_var($sql));
    }

    public function get_status_counts() {
        global $wpdb;
        $rows   = $wpdb->get_results("SELECT status, COUNT(*) as cnt FROM {$this->tickets_table} GROUP BY status");
        $counts = [];
        foreach ($rows as $row) {
            $counts[$row->status] = intval($row->cnt);
        }
        return $counts;
    }

    public function add_reply($ticket_id, $author_type, $author_id, $message) {
        global $wpdb;

        $wpdb->insert(
            $this->replies_table,
            [
                'ticket_id'   => $ticket_id,
                'author_type' => $author_type,
                'author_id'   => $author_id,
                'message'     => $message,
                'created_at'  => current_time('mysql'),
            ],
            ['%d','%s','%d','%s','%s']
        );

        if ($wpdb->last_error) return false;

        $wpdb->update(
            $this->tickets_table,
            ['updated_at' => current_time('mysql')],
            ['id' => $ticket_id],
            ['%s'],
            ['%d']
        );

        return true;
    }

    public function get_replies($ticket_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->replies_table} WHERE ticket_id = %d ORDER BY created_at ASC",
            $ticket_id
        ));
    }

    public function update_status($ticket_id, $status) {
        global $wpdb;
        return $wpdb->update(
            $this->tickets_table,
            ['status' => $status, 'updated_at' => current_time('mysql')],
            ['id' => $ticket_id],
            ['%s','%s'],
            ['%d']
        );
    }

    public function update_priority($ticket_id, $priority) {
        global $wpdb;
        return $wpdb->update(
            $this->tickets_table,
            ['priority' => $priority, 'updated_at' => current_time('mysql')],
            ['id' => $ticket_id],
            ['%s','%s'],
            ['%d']
        );
    }

    private function generate_ticket_number() {
        global $wpdb;
        $last = $wpdb->get_var("SELECT ticket_number FROM {$this->tickets_table} ORDER BY id DESC LIMIT 1");
        if ($last && preg_match('/^UT-(\d+)$/', $last, $m)) {
            $next = intval($m[1]) + 1;
        } else {
            $next = 1;
        }
        return 'UT-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
