<?php
/*
Plugin Name: Tribe Glomar
Description: Force the frontend of the site to hide if you are not logged in.
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Version: 1.0.3
*/

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('tribe_glomar_load') ) { // play nice
	function tribe_glomar_load() {
		if ( defined( 'TRIBE_GLOMAR' ) && TRIBE_GLOMAR === false ) return;
		if ( ! apply_filters( 'tribe_glomar', true ) ) return;

		// ok, we have permission to load
		require_once( 'Tribe_Glomar.php' );
		require_once( 'Tribe_Glomar_Admin.php' );
		Tribe_Glomar::init();
	}

	add_action( 'plugins_loaded', 'tribe_glomar_load' );
}
