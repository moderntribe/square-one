<?php
/*
Plugin Name: Tribe Roles
Description: Refine and manage the available roles
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Version: 1.0
*/

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('tribe_roles_load') ) { // play nice
	function tribe_roles_load() {

		// ok, we have permission to load
		require_once( 'Tribe_Roles.php' );
		add_action( 'plugins_loaded', array( 'Tribe_Roles', 'init' ), 0, 0 );
	}

	tribe_roles_load();
}
