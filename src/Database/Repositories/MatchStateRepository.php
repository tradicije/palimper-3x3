<?php
/**
 * Repository for match state records (one row per live match).
 *
 * @package Palimper\ThreeXThree
 * @license AGPL-3.0-or-later
 */

namespace Palimper\ThreeXThree\Database\Repositories;

defined( 'ABSPATH' ) || exit;

class MatchStateRepository {

    private \wpdb $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    private function table(): string {
        return $this->wpdb->prefix . 'p3x3_match_states';
    }

    /**
     * Returns the state row for a given match, or null if none exists.
     *
     * @return array<string, mixed>|null
     */
    public function find_by_match( int $match_id ): ?array {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM `{$this->table()}` WHERE match_id = %d LIMIT 1",
                $match_id
            ),
            ARRAY_A
        );

        return $row ?: null;
    }

    /**
     * Inserts or updates the state row for a match.
     *
     * Uses INSERT … ON DUPLICATE KEY UPDATE because the match_id column carries
     * a UNIQUE KEY constraint. All scalar values in $data must be provided by
     * the caller; updated_at is set automatically.
     *
     * Supported keys in $data:
     *   home_score, away_score, home_fouls, away_fouls,
     *   clock_seconds, shot_clock, period, timer_running
     *
     * @param array<string, mixed> $data
     */
    public function upsert( int $match_id, array $data ): bool {
        $table = $this->table();
        $now   = current_time( 'mysql', true );

        $home_score    = isset( $data['home_score'] )    ? (int) $data['home_score']    : 0;
        $away_score    = isset( $data['away_score'] )    ? (int) $data['away_score']    : 0;
        $home_fouls    = isset( $data['home_fouls'] )    ? (int) $data['home_fouls']    : 0;
        $away_fouls    = isset( $data['away_fouls'] )    ? (int) $data['away_fouls']    : 0;
        $clock_seconds = isset( $data['clock_seconds'] ) ? (int) $data['clock_seconds'] : 600;
        $shot_clock    = isset( $data['shot_clock'] )    ? (int) $data['shot_clock']    : 12;
        $period        = isset( $data['period'] )        ? (int) $data['period']        : 1;
        $timer_running = isset( $data['timer_running'] ) ? (int) $data['timer_running'] : 0;

        $result = $this->wpdb->query(
            $this->wpdb->prepare(
                "INSERT INTO `{$table}`
                    (match_id, home_score, away_score, home_fouls, away_fouls,
                     clock_seconds, shot_clock, period, timer_running, updated_at)
                 VALUES (%d, %d, %d, %d, %d, %d, %d, %d, %d, %s)
                 ON DUPLICATE KEY UPDATE
                    home_score    = VALUES(home_score),
                    away_score    = VALUES(away_score),
                    home_fouls    = VALUES(home_fouls),
                    away_fouls    = VALUES(away_fouls),
                    clock_seconds = VALUES(clock_seconds),
                    shot_clock    = VALUES(shot_clock),
                    period        = VALUES(period),
                    timer_running = VALUES(timer_running),
                    updated_at    = VALUES(updated_at)",
                $match_id,
                $home_score,
                $away_score,
                $home_fouls,
                $away_fouls,
                $clock_seconds,
                $shot_clock,
                $period,
                $timer_running,
                $now
            )
        );

        return $result !== false;
    }
}
