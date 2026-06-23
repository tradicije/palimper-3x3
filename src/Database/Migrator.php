<?php
/**
 * Database migration runner for Palimper 3x3.
 *
 * @package Palimper\ThreeXThree
 * @license AGPL-3.0-or-later
 */

namespace Palimper\ThreeXThree\Database;

defined( 'ABSPATH' ) || exit;

class Migrator {

    const DB_VERSION = '0.1.0';
    const OPTION_KEY = 'palimper_3x3_db_version';

    /**
     * Runs schema creation when the stored version does not match DB_VERSION.
     */
    public static function run(): void {
        if ( get_option( self::OPTION_KEY ) === self::DB_VERSION ) {
            return;
        }

        Schema::create();
        update_option( self::OPTION_KEY, self::DB_VERSION );
    }
}
