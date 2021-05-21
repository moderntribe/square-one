<?php declare(strict_types=1);

/*
Plugin Name: Do Not Cache
Plugin URI: http://tri.be
Description: Prevents page caching when the 'do_not_cache' action is called
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Version: 1.0
*/

/**
 * Disable page caching for the current page load
 */
function trigger_do_not_cache() {
	nocache_headers();
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
	if ( ! function_exists( 'batcache_cancel' ) ) {
		return;
	}

	batcache_cancel();
}
add_action( 'do_not_cache', 'trigger_do_not_cache' );

/**
 * Set nocache headers when circumstances indicate
 *
 * @return void
 */
function do_not_cache_set_headers() {
	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		trigger_do_not_cache();
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		trigger_do_not_cache();
	}
	if ( empty( $_REQUEST['update_feedwordpress'] ) ) {
		return;
	}

	trigger_do_not_cache();
}

add_action( 'init', 'do_not_cache_set_headers', 100, 0 );
