<?php

/**
 * Wrapper for object cache to enable selective cache bursting etc.
 */
class Tribe_ObjectCache {

	const OPTION_GROUP_KEYS = 'tribe_cache_group_keys';

	/** @var Tribe_ObjectCache */
	private static $instance;

	private function add_hooks() {
		add_action( 'init', array( $this, 'maybe_clear_cache' ), 9, 0 );
		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_button' ), 100, 1 );
		}
	}

	public function maybe_clear_cache() {
		if ( empty( $_REQUEST['tribe-clear-cache'] ) || empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'clear-cache' ) ) {
			return; // nothing to do here
		}
		self::flush_all();
		wp_redirect( remove_query_arg( array( 'tribe-clear-cache', '_wpnonce' ) ) );
		exit();
	}

	/**
	 * @param WP_Admin_Bar $admin_bar
	 */
	public function add_admin_bar_button( $admin_bar ) {
		$admin_bar->add_menu( array(
			'parent' => '',
			'id'     => 'clear-cache',
			'title'  => __( 'Clear Cache', 'tribe' ),
			'meta'   => array( 'title' => __( 'Clear the cache for this site', 'tribe' ) ),
			'href'   => wp_nonce_url( add_query_arg( array( 'tribe-clear-cache' => 1 ) ), 'clear-cache' ),
		) );
	}

	/**
	 * Process the cache key so that any unique data may serve as a key, even if it's an object or array.
	 *
	 * @param array|object|string $key
	 *
	 * @return bool|string
	 */
	private static function filter_key( $key ) {
		if ( empty( $key ) ) return false;
		$key = ( is_array( $key ) ) ? md5( serialize( $key ) ) : $key;

		return $key;
	}

	public static function set( $key, $value, $group = 'tribe', $expire = 0 ) {
		$group = self::get_group_key( $group );

		return wp_cache_set( self::filter_key( $key ), $value, $group, $expire );
	}

	public static function get( $key, $group = 'tribe' ) {
		$group   = self::get_group_key( $group );
		$results = wp_cache_get( self::filter_key( $key ), $group );

		return $results;
	}

	public static function delete( $key, $group = 'tribe' ) {
		$group   = self::get_group_key( $group );
		$results = wp_cache_delete( self::filter_key( $key ), $group );

		return $results;
	}

	/**
	 * Clear the cache on all blogs
	 */
	public static function flush_all_sites() {
		global $wp_object_cache;
		if ( isset( $wp_object_cache->mc ) ) {
			foreach ( array_keys( $wp_object_cache->mc ) as $group ) {
				$wp_object_cache->mc[$group]->flush();
			}
		}
	}

	/**
	 * Change the key for everything we've tracked,
	 * thereby flushing the cache for a blog
	 */
	public static function flush_all() {
		$keys = self::get_group_keys();
		$time = time();
		foreach ( $keys as $key => &$value ) {
			$value = $key . $time;
		}
		self::set_group_keys( $keys );

		// signal batcache to flush everything for this domain
		wp_cache_add_global_groups( 'tribe-cache-cleared' );
		wp_cache_set( $_SERVER['HTTP_HOST'], $time, 'tribe-cache-cleared' );
	}

	public static function flush_group( $group = 'tribe' ) {
		self::update_group_key( $group );
	}

	private static function set_group_keys( array $keys ) {
		update_option( self::OPTION_GROUP_KEYS, $keys );
	}

	private static function get_group_keys() {
		$keys = get_option( self::OPTION_GROUP_KEYS, array() );
		if ( empty( $keys ) || ! is_array( $keys ) ) {
			$keys = array();
		};

		return $keys;
	}

	private static function get_group_key( $group ) {
		$keys = self::get_group_keys();
		if ( isset( $keys[$group] ) ) {
			return $keys[$group];
		}
		// make a new key
		$group = self::update_group_key( $group );

		return $group;
	}

	private static function update_group_key( $group ) {
		$keys         = self::get_group_keys();
		$new          = $group . time();
		$keys[$group] = $new;
		self::set_group_keys( $keys );

		return $new;
	}

	/********** Singleton *************/

	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		if ( function_exists( 'wp_cache_get' ) ) {
			self::$instance = self::get_instance();
		}
	}

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 * @static
	 * @return Tribe_ObjectCache
	 */
	public static function get_instance() {
		if ( ! is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	final public function __clone() {
		trigger_error( "Singleton. No cloning allowed!", E_USER_ERROR );
	}

	final public function __wakeup() {
		trigger_error( "Singleton. No serialization allowed!", E_USER_ERROR );
	}

	protected function __construct() {
		$this->add_hooks();
	}
}

