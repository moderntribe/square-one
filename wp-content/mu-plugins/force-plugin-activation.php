<?php declare(strict_types=1);

namespace Tribe\Mu;

/**
 * Plugin Name:  Force Plugin Activation/Deactivation
 * Plugin URI:   https://tri.be
 * Description:  Make sure the required plugins are always active.
 * Version:      1.0
 * Author:       Modern Tribe
 * Author URI:   https://tri.be
 */

class Force_Plugin_Activation {

	/**
	 * These plugins will always be forced active.
	 *
	 * Add elements as plugin path: directory/file.php
	 *
	 * @var string[]
	 */
	private array $force_active = [
		'acf-image-select/acf-image-select.php',
		'advanced-custom-fields-pro/acf.php',
		'core/core.php',
		'disable-emojis/disable-emojis.php',
		'tribe-acf-post-list-field/tribe-acf-post-list-field.php',
	];

	/**
	 * These plugins will be deactivated and can't
	 * be activated unless SQ1_DEBUG is true.
	 *
	 * Add elements as plugin path: directory/file.php
	 *
	 * @var string[]
	 */
	private array $force_inactive = [
		'debug-bar-action-hooks/debug-bar-action-hooks.php',
		'debug-bar-console/debug-bar-console.php',
		'debug-bar-cron/debug-bar-cron.php',
		'debug-bar-extender/debug-bar-extender.php',
		'debug-bar/debug-bar.php',
		'rewrite-rules-inspector/rewrite-rules-inspector.php',
		'wp-log-in-browser/wp-log-in-browser.php',
		'wp-tota11y/wp-tota11y.php',
		'wp-xhprof-profiler/xhprof-profiler.php',
	];

	/**
	 * These plugins will not show in the site plugins list.
	 * They will only show in the network admin.
	 *
	 * Add elements as plugin path: directory/file.php
	 *
	 * @var string[]
	 */
	private array $force_network_only = [
		'advanced-custom-fields-pro/acf.php',
		'debug-bar-action-hooks/debug-bar-action-hooks.php',
		'debug-bar-console/debug-bar-console.php',
		'debug-bar-cron/debug-bar-cron.php',
		'debug-bar-extender/debug-bar-extender.php',
		'debug-bar/debug-bar.php',
		'rewrite-rules-inspector/rewrite-rules-inspector.php',
		'wp-log-in-browser/wp-log-in-browser.php',
		'wp-tota11y/wp-tota11y.php',
		'wp-xhprof-profiler/xhprof-profiler.php',
	];

	public function __construct() {

		// Always block non-production sites from search engines and random visitors.
		if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) || WP_ENVIRONMENT_TYPE !== 'production' ) {
			$this->force_active[] = 'tribe-glomar/tribe-glomar.php';
		}

		/**
		 * If you are about to refactor this, pay attention.
		 * The next *if* is not the same as an *else* on the previous one.
		 *
		 * Also, wp_get_environment_type defaults to 'production' if the constant is
		 * not set, so we should always look for the explicit definition before doing
		 * anything here.
		 */
		if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'production' ) {
			$this->force_inactive[] = 'tribe-glomar/tribe-glomar.php';
			$this->force_active[]   = 'limit-login-attempts-reloaded/limit-login-attempts-reloaded.php';
		}

		// Specific config when unit tests are running
		if ( defined( 'DIR_TESTDATA' ) && DIR_TESTDATA ) {
			//$this->force_inactivate[] = 'term-sorter/term-sorter.php';
		}

		add_filter( 'option_active_plugins', [ $this, 'force_plugins' ], 10, 1 );
		add_filter( 'site_option_active_sitewide_plugins', [ $this, 'force_plugins' ], 10, 1 );
		add_filter( 'plugin_action_links', [ $this, 'plugin_action_links' ], 99, 2 );
		add_filter( 'network_admin_plugin_action_links', [ $this, 'plugin_action_links' ], 99, 2 );
		add_filter( 'all_plugins', [ $this, 'hide_from_blog' ], 99, 1 );
	}

	/**
	 * Enforce the activate/deactivate plugin rules
	 *
	 * @param array|bool $plugins
	 *
	 * @return array|bool
	 */
	public function force_plugins( $plugins ) {

		// Occasionally it seems a boolean can be passed in here.
		if ( ! is_array( $plugins ) ) {
			return $plugins;
		}

		/*
		 * WordPress works in mysterious ways
		 * active_plugins has the plugin paths as array key and a number as value
		 * active_sitewide_plugins has the number as key and the plugin path as value
		 * I'm standardizing so we can run the array operations below, then flipping back if needed.
		 */
		if ( current_filter() === 'site_option_active_sitewide_plugins' ) {
			$plugins = array_flip( $plugins );

			if ( empty( $plugins ) ) {
				return $plugins;
			}
		}

		// Add our force-activated plugins
		$plugins = array_merge( $plugins, $this->force_active );

		// Remove our force-deactivated plugins unless SQ1_DEBUG is on. Forced removal when unit tests are running
		if ( ( defined( 'DIR_TESTDATA' ) && DIR_TESTDATA ) || ! defined( 'SQ1_DEBUG' ) || ! SQ1_DEBUG ) {
			$plugins = array_diff( $plugins, $this->force_inactive );
		}

		// Remove duplicates
		$plugins = array_unique( $plugins );

		// Flip back if needed (see comment above)
		if ( current_filter() === 'site_option_active_sitewide_plugins' ) {
			$plugins = array_flip( $plugins );
		}

		return $plugins;
	}

	/**
	 * Removes the activate/deactivate links from the plugins' list
	 * if they are in the force active or force inactive lists.
	 *
	 * @param array  $actions
	 * @param string $plugin_file
	 *
	 * @return array
	 */
	public function plugin_action_links( array $actions, string $plugin_file ): array {

		if ( in_array( $plugin_file, $this->force_active ) ) {
			unset( $actions['deactivate'] );
		}

		if ( ! defined( 'SQ1_DEBUG' ) || ! SQ1_DEBUG ) {
			if ( in_array( $plugin_file, $this->force_inactive ) ) {
				unset( $actions['activate'] );
			}
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
	 * @return string[]
	 */
	public function hide_from_blog( array $plugins ): array {

		if ( ! is_multisite() ) {
			return $plugins;
		}

		$screen = get_current_screen();

		if ( $screen && $screen->in_admin( 'network' ) ) {
			return $plugins;
		}

		foreach ( (array) $this->force_network_only as $slug ) {
			if ( ! isset( $plugins[ $slug ] ) ) {
				continue;
			}

			unset( $plugins[ $slug ] );
		}

		return $plugins;
	}

}

new Force_Plugin_Activation();
