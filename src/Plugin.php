<?php

namespace Palimper\ThreeXThree;

use Palimper\ThreeXThree\Database\Migrator;
use Palimper\ThreeXThree\WordPress\AdminMenu;
use Palimper\ThreeXThree\WordPress\AssetLoader;
use Palimper\ThreeXThree\WordPress\Capabilities;

class Plugin {
	private static ?self $instance = null;

	public static function boot(): void {
		if ( self::$instance !== null ) {
			return;
		}
		self::$instance = new self();
	}

	private function __construct() {
		register_activation_hook( PALIMPER_3X3_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( PALIMPER_3X3_FILE, [ $this, 'deactivate' ] );
		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_menu', [ new AdminMenu(), 'register' ] );
		add_action( 'admin_enqueue_scripts', [ new AssetLoader(), 'register' ] );
	}

	public function activate(): void {
		( new Capabilities() )->register();
		Migrator::run();
	}

	public function deactivate(): void {
		( new Capabilities() )->remove();
	}

	public function init(): void {
		load_plugin_textdomain( 'palimper-3x3', false, dirname( plugin_basename( PALIMPER_3X3_FILE ) ) . '/languages' );
	}
}
