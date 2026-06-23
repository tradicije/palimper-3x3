<?php
/**
 * Database schema definitions for Palimper 3x3.
 *
 * @package Palimper\ThreeXThree
 * @license AGPL-3.0-or-later
 */

namespace Palimper\ThreeXThree\Database;

defined( 'ABSPATH' ) || exit;

class Schema {

    /**
     * Returns a map of logical table names to their full prefixed names.
     *
     * @return array<string, string>
     */
    public static function tables(): array {
        global $wpdb;

        return [
            'tournaments'   => $wpdb->prefix . 'p3x3_tournaments',
            'teams'         => $wpdb->prefix . 'p3x3_teams',
            'groups'        => $wpdb->prefix . 'p3x3_groups',
            'group_teams'   => $wpdb->prefix . 'p3x3_group_teams',
            'matches'       => $wpdb->prefix . 'p3x3_matches',
            'match_states'  => $wpdb->prefix . 'p3x3_match_states',
            'match_events'  => $wpdb->prefix . 'p3x3_match_events',
        ];
    }

    /**
     * Creates or upgrades all plugin tables via dbDelta().
     */
    public static function create(): void {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();
        $t               = self::tables();

        $sql = [];

        $sql[] = "CREATE TABLE `{$t['tournaments']}` (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'draft',
            format VARCHAR(20) NOT NULL DEFAULT 'groups',
            location VARCHAR(200) NOT NULL DEFAULT '',
            scheduled_at DATETIME DEFAULT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB {$charset_collate};";

        $sql[] = "CREATE TABLE `{$t['teams']}` (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            tournament_id BIGINT UNSIGNED NOT NULL,
            name VARCHAR(200) NOT NULL,
            seed TINYINT UNSIGNED NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY tournament_id (tournament_id)
        ) ENGINE=InnoDB {$charset_collate};";

        $sql[] = "CREATE TABLE `{$t['groups']}` (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            tournament_id BIGINT UNSIGNED NOT NULL,
            name VARCHAR(100) NOT NULL,
            position TINYINT UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY tournament_id (tournament_id)
        ) ENGINE=InnoDB {$charset_collate};";

        $sql[] = "CREATE TABLE `{$t['group_teams']}` (
            group_id BIGINT UNSIGNED NOT NULL,
            team_id BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (group_id, team_id)
        ) ENGINE=InnoDB {$charset_collate};";

        $sql[] = "CREATE TABLE `{$t['matches']}` (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            tournament_id BIGINT UNSIGNED NOT NULL,
            group_id BIGINT UNSIGNED DEFAULT NULL,
            stage VARCHAR(20) NOT NULL DEFAULT 'group',
            round TINYINT UNSIGNED NOT NULL DEFAULT 0,
            position TINYINT UNSIGNED NOT NULL DEFAULT 0,
            home_team_id BIGINT UNSIGNED DEFAULT NULL,
            away_team_id BIGINT UNSIGNED DEFAULT NULL,
            home_score TINYINT UNSIGNED NOT NULL DEFAULT 0,
            away_score TINYINT UNSIGNED NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            scheduled_at DATETIME DEFAULT NULL,
            finished_at DATETIME DEFAULT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY tournament_id (tournament_id),
            KEY status (status)
        ) ENGINE=InnoDB {$charset_collate};";

        $sql[] = "CREATE TABLE `{$t['match_states']}` (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            match_id BIGINT UNSIGNED NOT NULL,
            home_score TINYINT UNSIGNED NOT NULL DEFAULT 0,
            away_score TINYINT UNSIGNED NOT NULL DEFAULT 0,
            home_fouls TINYINT UNSIGNED NOT NULL DEFAULT 0,
            away_fouls TINYINT UNSIGNED NOT NULL DEFAULT 0,
            clock_seconds SMALLINT UNSIGNED NOT NULL DEFAULT 600,
            shot_clock TINYINT UNSIGNED NOT NULL DEFAULT 12,
            period TINYINT UNSIGNED NOT NULL DEFAULT 1,
            timer_running TINYINT(1) NOT NULL DEFAULT 0,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY match_id (match_id)
        ) ENGINE=InnoDB {$charset_collate};";

        $sql[] = "CREATE TABLE `{$t['match_events']}` (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            match_id BIGINT UNSIGNED NOT NULL,
            operator_id BIGINT UNSIGNED NOT NULL,
            event_type VARCHAR(50) NOT NULL,
            payload TEXT NOT NULL DEFAULT '{}',
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY match_id (match_id)
        ) ENGINE=InnoDB {$charset_collate};";

        foreach ( $sql as $statement ) {
            dbDelta( $statement );
        }
    }
}
