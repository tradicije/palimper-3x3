<?php

namespace Palimper\ThreeXThree\WordPress;

class AdminMenu {
	public function register(): void {
		add_menu_page(
			__( '3x3 Tournaments', 'palimper-3x3' ),
			__( '3x3 Tournaments', 'palimper-3x3' ),
			'manage_palimper_tournaments',
			'palimper-3x3',
			[ $this, 'page_tournaments' ],
			'dashicons-awards'
		);

		add_submenu_page(
			'palimper-3x3',
			__( 'Tournaments', 'palimper-3x3' ),
			__( 'Tournaments', 'palimper-3x3' ),
			'manage_palimper_tournaments',
			'palimper-3x3',
			[ $this, 'page_tournaments' ]
		);

		add_submenu_page(
			'palimper-3x3',
			__( 'Teams', 'palimper-3x3' ),
			__( 'Teams', 'palimper-3x3' ),
			'manage_palimper_tournaments',
			'palimper-3x3-teams',
			[ $this, 'page_teams' ]
		);

		add_submenu_page(
			'palimper-3x3',
			__( 'Matches', 'palimper-3x3' ),
			__( 'Matches', 'palimper-3x3' ),
			'manage_palimper_matches',
			'palimper-3x3-matches',
			[ $this, 'page_matches' ]
		);

		add_submenu_page(
			'palimper-3x3',
			__( 'Settings', 'palimper-3x3' ),
			__( 'Settings', 'palimper-3x3' ),
			'manage_palimper_tournaments',
			'palimper-3x3-settings',
			[ $this, 'page_settings' ]
		);
	}

	public function page_tournaments(): void {
		if ( ! current_user_can( 'manage_palimper_tournaments' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'palimper-3x3' ) );
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Tournaments', 'palimper-3x3' ); ?></h1>
			<p><?php esc_html_e( 'Coming soon.', 'palimper-3x3' ); ?></p>
		</div>
		<?php
	}

	public function page_teams(): void {
		if ( ! current_user_can( 'manage_palimper_tournaments' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'palimper-3x3' ) );
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Teams', 'palimper-3x3' ); ?></h1>
			<p><?php esc_html_e( 'Coming soon.', 'palimper-3x3' ); ?></p>
		</div>
		<?php
	}

	public function page_matches(): void {
		if ( ! current_user_can( 'manage_palimper_matches' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'palimper-3x3' ) );
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Matches', 'palimper-3x3' ); ?></h1>
			<p><?php esc_html_e( 'Coming soon.', 'palimper-3x3' ); ?></p>
		</div>
		<?php
	}

	public function page_settings(): void {
		if ( ! current_user_can( 'manage_palimper_tournaments' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'palimper-3x3' ) );
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Settings', 'palimper-3x3' ); ?></h1>
			<p><?php esc_html_e( 'Coming soon.', 'palimper-3x3' ); ?></p>
		</div>
		<?php
	}
}
