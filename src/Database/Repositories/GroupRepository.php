<?php
/**
 * Repository for group records and group-team pivot rows.
 *
 * @package Palimper\ThreeXThree
 * @license AGPL-3.0-or-later
 */

namespace Palimper\ThreeXThree\Database\Repositories;

defined( 'ABSPATH' ) || exit;

class GroupRepository {

    private \wpdb $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    private function table(): string {
        return $this->wpdb->prefix . 'p3x3_groups';
    }

    private function pivot_table(): string {
        return $this->wpdb->prefix . 'p3x3_group_teams';
    }

    /**
     * Finds a single group by its primary key.
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
     * Returns all groups for a tournament ordered by position.
     *
     * @return array<int, array<string, mixed>>
     */
    public function find_all_by_tournament( int $tournament_id ): array {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM `{$this->table()}` WHERE tournament_id = %d ORDER BY position ASC, id ASC",
                $tournament_id
            ),
            ARRAY_A
        ) ?: [];
    }

    /**
     * Inserts a new group record.
     *
     * @param array<string, mixed> $data
     * @return int Inserted row ID.
     */
    public function insert( array $data ): int {
        $this->wpdb->insert( $this->table(), $data );

        return (int) $this->wpdb->insert_id;
    }

    /**
     * Updates an existing group record.
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
     * Deletes a group by primary key.
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
     * Assigns a team to a group (inserts pivot row, ignores duplicates).
     */
    public function assign_team( int $group_id, int $team_id ): bool {
        $result = $this->wpdb->query(
            $this->wpdb->prepare(
                "INSERT IGNORE INTO `{$this->pivot_table()}` (group_id, team_id) VALUES (%d, %d)",
                $group_id,
                $team_id
            )
        );

        return $result !== false;
    }

    /**
     * Removes a team from a group (deletes pivot row).
     */
    public function remove_team( int $group_id, int $team_id ): bool {
        $result = $this->wpdb->delete(
            $this->pivot_table(),
            [
                'group_id' => $group_id,
                'team_id'  => $team_id,
            ],
            [ '%d', '%d' ]
        );

        return $result !== false && $result > 0;
    }

    /**
     * Returns all team rows assigned to a group via the pivot table.
     *
     * @return array<int, array<string, mixed>>
     */
    public function find_teams( int $group_id ): array {
        $teams_table = $this->wpdb->prefix . 'p3x3_teams';
        $pivot       = $this->pivot_table();

        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT t.* FROM `{$teams_table}` t
                 INNER JOIN `{$pivot}` gt ON gt.team_id = t.id
                 WHERE gt.group_id = %d
                 ORDER BY t.seed ASC, t.id ASC",
                $group_id
            ),
            ARRAY_A
        ) ?: [];
    }
}
