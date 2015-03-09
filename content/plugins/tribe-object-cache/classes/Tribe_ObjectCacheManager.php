<?php

/**
 * Tracks WP events and expires select caches as necessary
 */
class Tribe_ObjectCacheManager {
	const DB_VERSION = 1;
	const CRON_HOOK  = 'tribe_object_cache_cron';

	/** @var Tribe_ObjectCacheManager */
	private static $instance;

	private function add_hooks() {
		// include each hook that should lead to something expiring
		// add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		// add_action( 'wp_update_nav_menu_item', array( $this, 'update_menu' ), 10, 0 );
	}

	/**
	 * A post has been created, updated, or trashed
	 *
	 * @param int    $post_id
	 * @param object $post
	 */
	public function save_post( $post_id, $post ) {
		// Example usage:
		// Tribe_ObjectCache::delete('some_cache_key', 'tribe');
	}

	/**
	 * A Menu has been edited or repositioned
	 */
	public function update_menu() {
	}

	/********** Singleton *************/

	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		if ( class_exists( 'Tribe_ObjectCache' ) ) {
			self::$instance = self::get_instance();
		}
	}

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 * @static
	 * @return Tribe_ObjectCacheManager
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

