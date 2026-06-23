<?php
/**
 * Plugin Name: Palimper 3x3
 * Plugin URI:  https://palimper.net
 * Description: FIBA 3x3 basketball tournament management.
 * Version:     0.1.0
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author:      Palimper
 * License:     AGPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/agpl-3.0.html
 * Text Domain: palimper-3x3
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'PALIMPER_3X3_FILE', __FILE__ );
define( 'PALIMPER_3X3_DIR', plugin_dir_path( __FILE__ ) );
define( 'PALIMPER_3X3_URL', plugin_dir_url( __FILE__ ) );

spl_autoload_register( static function ( string $class ): void {
	$prefix = 'Palimper\\ThreeXThree\\';
	if ( strncmp( $prefix, $class, strlen( $prefix ) ) !== 0 ) {
		return;
	}
	$relative = substr( $class, strlen( $prefix ) );
	$file     = __DIR__ . '/src/' . str_replace( '\\', '/', $relative ) . '.php';
	if ( file_exists( $file ) ) {
		require $file;
	}
} );

add_action( 'plugins_loaded', [ \Palimper\ThreeXThree\Plugin::class, 'boot' ] );
