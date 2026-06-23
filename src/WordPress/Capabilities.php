<?php

namespace Palimper\ThreeXThree\WordPress;

class Capabilities {
	private const CAPS = [
		'manage_palimper_tournaments',
		'manage_palimper_matches',
		'operate_palimper_scoreboard',
	];

	public function register(): void {
		$role = get_role( 'administrator' );
		if ( ! $role ) {
			return;
		}
		foreach ( self::CAPS as $cap ) {
			$role->add_cap( $cap );
		}
	}

	public function remove(): void {
		$role = get_role( 'administrator' );
		if ( ! $role ) {
			return;
		}
		foreach ( self::CAPS as $cap ) {
			$role->remove_cap( $cap );
		}
	}
}
