<?php
/*
Plugin Name: Tribe Admin Dashboard
Description: Override the admin dashboard with a totally custom single screen admin.
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Version: 1.0
*/

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('tribe_admin_dashboard_load') ) { // play nice
	function tribe_admin_dashboard_load() {
		require_once('Tribe_AdminDashboard.php');
		add_action( 'plugins_loaded', array( 'Tribe_AdminDashboard', 'init' ) );
	}

	tribe_admin_dashboard_load();
}
