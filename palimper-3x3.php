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

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		static function (): void {
			echo '<div class="notice notice-error"><p>'
				. esc_html__( 'Palimper 3x3: Composer dependencies are missing. Run `composer install` in the plugin directory.', 'palimper-3x3' )
				. '</p></div>';
		}
	);
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

add_action( 'plugins_loaded', [ \Palimper\ThreeXThree\Plugin::class, 'boot' ] );
