<?php
/**
 * Repository for team records.
 *
 * @package Palimper\ThreeXThree
 * @license AGPL-3.0-or-later
 */

namespace Palimper\ThreeXThree\Database\Repositories;

defined( 'ABSPATH' ) || exit;

class TeamRepository {

    private \wpdb $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    private function table(): string {
        return $this->wpdb->prefix . 'p3x3_teams';
    }

    /**
     * Finds a single team by its primary key.
     *
     * @return array<string, mixed>|null
     */
    public function find_by_id( int $id ): ?array {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM `{$this->table()}` WHERE id = %d LIMIT 1",
                $id
            ),
            ARRAY_A
        );

        return $row ?: null;
    }

    /**
     * Returns all teams belonging to a tournament, ordered by seed then id.
     *
     * @return array<int, array<string, mixed>>
     */
    public function find_all_by_tournament( int $tournament_id ): array {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM `{$this->table()}` WHERE tournament_id = %d ORDER BY seed ASC, id ASC",
                $tournament_id
            ),
            ARRAY_A
        ) ?: [];
    }

    /**
     * Inserts a new team record.
     *
     * @param array<string, mixed> $data
     * @return int Inserted row ID.
     */
    public function insert( array $data ): int {
        $data['created_at'] = current_time( 'mysql', true );

        $this->wpdb->insert( $this->table(), $data );

        return (int) $this->wpdb->insert_id;
    }

    /**
     * Updates an existing team record.
     *
     * @param array<string, mixed> $data
     */
    public function update( int $id, array $data ): bool {
        $result = $this->wpdb->update(
            $this->table(),
            $data,
            [ 'id' => $id ]
        );

        return $result !== false;
    }

    /**
     * Deletes a team by primary key.
     */
    public function delete( int $id ): bool {
        $result = $this->wpdb->delete(
            $this->table(),
            [ 'id' => $id ],
            [ '%d' ]
        );

        return $result !== false && $result > 0;
    }

    /**
     * Deletes all teams belonging to a tournament.
     */
    public function delete_by_tournament( int $tournament_id ): bool {
        $result = $this->wpdb->delete(
            $this->table(),
            [ 'tournament_id' => $tournament_id ],
            [ '%d' ]
        );

        return $result !== false;
    }
}
