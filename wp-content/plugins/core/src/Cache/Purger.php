<?php


namespace Tribe\Project\Cache;

/**
 * Class Purger
 *
 * An admin bar control for completely purging the cache
 */
class Purger {
	private $query_arg = 'tribe-clear-cache';
	private $nonce_action = 'clear-cache';

	/**
	 * Hook into WordPress to register the button and its handler
	 * @return void
	 */
	public function hook() {
		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'init', array( $this, 'maybe_purge_cache' ), 9, 0 );
			add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_button' ), 100, 1 );
		}
	}

	/**
	 * Handle requests to clear the cache
	 *
	 * @return void
	 */
	public function maybe_purge_cache() {
		if ( empty( $_REQUEST[ $this->query_arg ] ) || empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], $this->nonce_action ) ) {
			return; // nothing to do here
		}
		$this->do_purge_cache();
		wp_redirect( esc_url_raw( remove_query_arg( array( $this->query_arg, '_wpnonce' ) ) ) );
		exit();
	}

	/**
	 * Purge everything from the cache
	 *
	 * TODO: break this into separate strategy classes
	 * @return void
	 */
	private function do_purge_cache() {
		if ( class_exists( 'WpeCommon' ) ) {
			\WpeCommon::purge_memcached();
			\WpeCommon::clear_maxcdn_cache();
			\WpeCommon::purge_varnish_cache();
		} else {
			wp_cache_flush();
			if ( function_exists( 'opcache_reset' ) ) {
				opcache_reset();
			}
		}

		do_action( 'tribe_purge_cache' );
	}

	/**
	 * @param \WP_Admin_Bar $admin_bar
	 */
	public function add_admin_bar_button( $admin_bar ) {
		$admin_bar->add_menu( array(
			'parent' => '',
			'id'     => 'clear-cache',
			'title'  => __( 'Clear Cache', 'tribe' ),
			'meta'   => array( 'title' => __( 'Clear the cache for this site', 'tribe' ) ),
			'href'   => wp_nonce_url( add_query_arg( array( $this->query_arg => 1 ) ), $this->nonce_action ),
		) );
	}

}