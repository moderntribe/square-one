<?php

/**
 * Class Tribe_Security
 *
 * Refine and manage the available roles
 */
class Tribe_Security {

	/** @var Tribe_Security */
	private static $instance;

	/**
	 * Add the hooks
	 */
	private function add_hooks() {
		$this->xmlrpc_filters();
		$this->clean_wp_filters();
	}

	/**
	 * XMLRPC security filters
	 */
	private function xmlrpc_filters() {

		// Disable XMLRPC all together by default
		if ( !defined('USE_XMLRPC') || USE_XMLRPC == false ) {
			add_filter('xmlrpc_enabled', '__return_false');
			return;
		}

		// Disable XMLRPC pingback
		// @see http://blog.sucuri.net/2014/03/more-than-162000-wordpress-sites-used-for-distributed-denial-of-service-attack.html
		if ( !defined('USE_XMLRPC_PINGBACK') || USE_XMLRPC_PINGBACK == false ) {
			add_filter( 'xmlrpc_methods', function( $methods ) {
				unset( $methods['pingback.ping'] );
				return $methods;
			} );
		}

	}

	/**
	 * WordPress clean up wp_head
	 */
	private function clean_wp_filters() {

		// Remove rsd_link 
		// Only necessary if adminstering the site using some type of
		// integrated software or application
		remove_action( 'wp_head', 'rsd_link' );
		
		// Remove wlwmanifest_link 
		// Only necessary if adminstering the site using Windows Live Writer
		remove_action( 'wp_head', 'wlwmanifest_link' );

		// Remove any extra unecssary feed links
		remove_action( 'wp_head', 'feed_links_extra', 3 );

		// Remove various unecessary pagination and location anchors
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );

		// Remove WordPress version number
		remove_action( 'wp_head', 'wp_generator' );
		add_filter( 'the_generator', function() {
		    return '';
		});

	}

	/********** Singleton *************/

	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		self::$instance = self::get_instance();
		self::$instance->add_hooks();
	}

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 * @static
	 * @return Tribe_Security
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

	protected function __construct() {}
}

