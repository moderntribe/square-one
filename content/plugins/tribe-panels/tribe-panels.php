<?php
/*
Plugin Name: Tribe Panels
Description: Defines the panels available on this site
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Version: 1.0
*/

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('tribe_panels_load') ) { // play nice
	function tribe_roles_load() {

		// ok, we have permission to load
		require_once( 'Tribe_Panels.php' );
		add_action( 'plugins_loaded', array( 'Tribe_Panels', 'initialize_panels' ), 0, 0 );
	}

	add_action( 'panels_init', 'tribe_panels_load' );
}
