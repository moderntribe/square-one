<?php


namespace Tribe\Project\Theme\Resources;


class Emoji_Disabler {
	public function hook() {
		add_action( 'after_setup_theme', [ $this, 'remove_hooks' ], 10, 0 ) ;
	}

	/**
	 * Remove WP Emoji Scripts
	 */
	public function remove_hooks() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}
}