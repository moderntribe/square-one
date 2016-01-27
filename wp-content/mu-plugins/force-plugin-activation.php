<?php
/*
Plugin Name:	Force Plugin Activation/Deactivation (except if WP_DEBUG is on)
Plugin URI: 	http://danieldvork.in
Description:	Make sure the required plugins are always active.
Version:    	1.0
Author:     	Daniel Dvorkin
Author URI: 	http://danieldvork.in
*/

class Force_Plugin_Activation {

	/**
	 * These plugins will always be active (if WP_DEBUG is false)
	 * and admins (or super) won't be able to deactivate them.
	 *
	 * Add elements as plugin path: directory/file.php
	 */
	private $force_active = array(
		'limit-login-attempts/limit-login-attempts.php',
		'tribe-admin-dashboard/tribe-admin-dashboard.php',
		'tribe-admin-ui/tribe-admin-ui.php',
		'tribe-object-cache/tribe-object-cache.php',
		'tribe-panels/tribe-panels.php',
		'tribe-roles/tribe-roles.php',
		'tribe-security/tribe-security.php',
	);

	/**
	 * These plugins will be deactived and can't be activated (if WP_DEBUG is false)
	 *
	 * Add elements as plugin path: directory/file.php
	 */
	private $force_deactive = array(
		'debug-bar/debug-bar.php',
		'debug-bar-action-hooks/debug-bar-action-hooks.php',
		'debug-bar-console/debug-bar-console.php',
		'debug-bar-cron/debug-bar-cron.php',
		'debug-bar-extender/debug-bar-extender.php',
		'rewrite-rules-inspector/rewrite-rules-inspector.php',
		'wp-log-in-browser/wp-log-in-browser.php',
		'wp-xhprof-profiler/xhprof-profiler.php'
	);

	/**
	 * These plugins will not show in the site plugins list.
	 * They will only show in the network admin.
	 *
	 * Add elements as plugin path: directory/file.php
	 */
	private $force_network_only = array(
		'debug-bar/debug-bar.php',
		'debug-bar-action-hooks/debug-bar-action-hooks.php',
		'debug-bar-console/debug-bar-console.php',
		'debug-bar-cron/debug-bar-cron.php',
		'debug-bar-extender/debug-bar-extender.php',
		'rewrite-rules-inspector/rewrite-rules-inspector.php',
		'wp-log-in-browser/wp-log-in-browser.php',
		'wp-xhprof-profiler/xhprof-profiler.php'
	);


	function __construct() {

		// Always block non-production sites from search engines and random visitors.
		if ( ! defined( 'ENVIRONMENT' ) || ENVIRONMENT != 'PRODUCTION' ) {
			$this->force_active[] = 'tribe-glomar/tribe-glomar.php';
		}
		// If you are about to refactor this, pay attention.
		// The next *if* is not the same as an *else* on the previous one.
		if ( defined( 'ENVIRONMENT' ) && ENVIRONMENT == 'PRODUCTION' ) {
			$this->force_deactive[] = 'tribe-glomar/tribe-glomar.php';
		}


		add_filter( 'option_active_plugins',               array( $this, 'force_plugins'       ), 10, 1 );
		add_filter( 'site_option_active_sitewide_plugins', array( $this, 'force_plugins'       ), 10, 1 );
		add_filter( 'plugin_action_links',                 array( $this, 'plugin_action_links' ), 99, 4 );
		add_filter( 'network_admin_plugin_action_links',   array( $this, 'plugin_action_links' ), 99, 4 );
		add_filter( 'all_plugins',                         array( $this, 'hide_from_blog'      ), 99, 1 );
	}

	/**
	 * Enforce the active/deactive plugin rules
	 *
	 * @param array $plugins
	 *
	 * @return array
	 */
	function force_plugins( $plugins ) {

		/*
		 * WordPress works in mysterious ways
		 * active_plugins has the plugin paths as array key and a number as value
		 * active_sitewide_plugins has the number as key and the plugin path as value
		 * I'm standarizing so we can run the array operations below, then flipping back if needed.
		 */
		if ( current_filter() == 'site_option_active_sitewide_plugins' ) {
			$plugins = array_flip( $plugins );
		}

		// Add our force-activated plugins
		$plugins = array_merge( (array) $plugins, $this->force_active );

		// Remove our force-deactivated plguins
		$plugins = array_diff( (array) $plugins, $this->force_deactive );

		// Deduplicate
		$plugins = array_unique( $plugins );

		// Flip back if needed (see comment above)
		if ( current_filter() == 'site_option_active_sitewide_plugins' ) {
			$plugins = array_flip( $plugins );
		}

		return $plugins;
	}

	/**
	 * Removes the activate/deactivate links from the plugins list
	 * if they are in the force active or force deactive lists.
	 *
	 * @param array $actions
	 * @param string $plugin_file
	 * @param array $plugin_data
	 * @param string $context
	 *
	 * @return array
	 */
	function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {

		if ( in_array( $plugin_file, $this->force_active ) ) {
			unset( $actions['deactivate'] );
		}

		if ( in_array( $plugin_file, $this->force_deactive ) ) {
			unset( $actions['activate'] );
		}

		return $actions;
	}

	/**
	 * Removes plugins from the blog plugins list
	 * if they are in the $force_network_only list
	 *
	 * Only on multisite.
	 *
	 * @param array $plugins
	 *
	 * @return array mixed
	 */
	function hide_from_blog( $plugins ) {

		if ( ! is_multisite() ) {
			return $plugins;
		}

		$screen = get_current_screen();
		if ( $screen->in_admin( 'network' ) ) {
			return $plugins;
		}

		foreach ( (array) $this->force_network_only as $slug ) {
			if ( isset( $plugins[ $slug ] ) ) {
				unset( $plugins[ $slug ] );
			}
		}

		return $plugins;
	}

}

// We want to enfornce only on production, where WP_DEBUG is off.
if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
	new Force_Plugin_Activation();
}