<?php
/**
 * Repository for match event records (append-only event log).
 *
 * @package Palimper\ThreeXThree
 * @license AGPL-3.0-or-later
 */

namespace Palimper\ThreeXThree\Database\Repositories;

defined( 'ABSPATH' ) || exit;

class MatchEventRepository {

    private \wpdb $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    private function table(): string {
        return $this->wpdb->prefix . 'p3x3_match_events';
    }

    /**
     * Inserts a new event for a match.
     *
     * @param array<string, mixed> $payload Arbitrary data; will be JSON-encoded.
     * @return int Inserted row ID.
     */
    public function insert_event( int $match_id, int $operator_id, string $type, array $payload ): int {
        $this->wpdb->insert(
            $this->table(),
            [
                'match_id'    => $match_id,
                'operator_id' => $operator_id,
                'event_type'  => $type,
                'payload'     => wp_json_encode( $payload ),
                'created_at'  => current_time( 'mysql', true ),
            ],
            [ '%d', '%d', '%s', '%s', '%s' ]
        );

        return (int) $this->wpdb->insert_id;
    }

    /**
     * Returns all events for a match in chronological order.
     *
     * @return array<int, array<string, mixed>>
     */
    public function find_by_match( int $match_id ): array {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM `{$this->table()}` WHERE match_id = %d ORDER BY id ASC",
                $match_id
            ),
            ARRAY_A
        ) ?: [];
    }
}
