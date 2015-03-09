<?php
/*
Plugin Name: Tribe Admin UI
Description: Customize & secure the WordPress admin UI
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Version: 1.0
*/

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('tribe_admin_ui_load') ) { // play nice
	function tribe_admin_ui_load() {
		require_once('Tribe_AdminUI.php');
		add_action( 'plugins_loaded', array( 'Tribe_AdminUI', 'init' ) );
	}

	tribe_admin_ui_load();
}
