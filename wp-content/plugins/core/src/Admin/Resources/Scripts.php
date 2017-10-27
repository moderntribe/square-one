<?php


namespace Tribe\Project\Admin\Resources;

class Scripts {
	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$manifest_scripts = $debug ? 'manifest.js' : 'manifest.min.js';
		$vendor_scripts = $debug ? 'vendor.js' : 'vendor.min.js';
		$admin_scripts = $debug ? 'scripts.js' : 'scripts.min.js';

		$manifest_src =  trailingslashit( get_stylesheet_directory_uri() ) . 'js/dist/admin/' . $manifest_scripts;
		$vendor_src = trailingslashit( get_stylesheet_directory_uri() ) . 'js/dist/admin/' . $vendor_scripts;
		$admin_src = trailingslashit( get_stylesheet_directory_uri() ) . 'js/dist/admin/' . $admin_scripts;

		wp_register_script( 'tribe-admin-manifest', $manifest_src, [ 'wp-util', 'media-upload', 'media-views' ], time(), true );
		wp_register_script( 'tribe-admin-vendors', $vendor_src, [ 'tribe-admin-manifest' ], time(), true );
		wp_register_script( 'tribe-admin-scripts', $admin_src, [ 'tribe-admin-vendors' ], time(), true );

		$js_config = new JS_Config();
		$js_l10n = new JS_Localization();
		wp_localize_script( 'jquery', 'modern_tribe_admin_config', $js_config->get_data() );
		wp_localize_script( 'jquery', 'modern_tribe_admin_i18n', $js_l10n->get_data() );

		/*
		 * Rather than enqueuing this immediately, delay until after
		 * admin_print_footer_scripts:50. This is when the WP visual
		 * editor prints the tinymce config.
		 */
		add_action( 'admin_print_footer_scripts', function () {

			wp_enqueue_script( 'tribe-admin-vendors' );
			wp_enqueue_script( 'tribe-admin-manifest' );
			wp_enqueue_script( 'tribe-admin-scripts' );

			// since footer scripts have already printed, process the queue again on the next available action
			add_action( "admin_footer-" . $GLOBALS['hook_suffix'], '_wp_footer_scripts' );
		}, 60, 0 );
	}
}
