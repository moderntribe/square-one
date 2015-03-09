<?php
/*
Plugin Name: Tribe Security
Description: Add a series of security best practices
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Version: 1.0
*/

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('tribe_security_load') ) { // play nice
	function tribe_security_load() {

		// ok, we have permission to load
		require_once( 'Tribe_Security.php' );
		add_action( 'plugins_loaded', array( 'Tribe_Security', 'init' ), 0, 0 );
	}

	tribe_security_load();
}
