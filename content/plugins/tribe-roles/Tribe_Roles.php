<?php

/**
 * Class Tribe_Roles
 *
 * Refine and manage the available roles
 */
class Tribe_Roles {

	/**
	 * Role schema version. Increment this to deploy role changes.
	 */
	const SCHEMA_VERSION = 0;

	/** @var Tribe_Roles */
	private static $instance;

	/**
	 * Add the hooks
	 */
	private function add_hooks() {
		if ( get_option( __CLASS__ . '_schema_version', 0 ) != self::SCHEMA_VERSION ) {
			add_action( 'init', array( $this, 'update_roles' ) );
		}
		add_filter( 'get_user_option_show_admin_bar_front', array( $this, 'filter_admin_bar' ), 10, 3 );
	}

	/**
	 * Update roles
	 */
	public function update_roles() {
		/*
		$version = get_option( __CLASS__ . '_schema_version', 0 );
		if ( $version < 1 ) {
			// Create an Example Role based on editor
			$editor             = get_role( 'editor' );
			$caps               = $editor->capabilities;
			$caps['edit_users'] = true;
			add_role( 'example-role', __( 'Example Role', 'tribe' ), $caps );
			$role = get_role( 'example-role' );
			$role->add_cap( 'list_users' );
			$role->add_cap( 'create_users' );
			$role->add_cap( 'remove_users' );
		}
		*/
		update_option( __CLASS__ . '_schema_version', self::SCHEMA_VERSION );
	}

	/**
	 * Hide admin bar for logged in subscribers
	 *
	 * @wordpress-filter get_user_option_show_admin_bar_front
	 *
	 * @param bool    $show
	 * @param string  $option
	 * @param WP_User $user
	 *
	 * @return string "true" or "false"
	 */
	public function filter_admin_bar( $show, $option, $user ) {
		if ( $this->is_subscriber( $user ) ) {
			return 'false';
		}

		return $show;
	}

	/**
	 * @param int|WP_User $user_id
	 *
	 * @return bool
	 */
	private function is_subscriber( $user_id = 0 ) {
		if ( is_object( $user_id ) ) {
			$user = $user_id;
		} else {
			$user_id = $user_id ? $user_id : get_current_user_id();
			$user    = new WP_User( $user_id );
		}
		$roles = $user->roles;
		if ( in_array( 'subscriber', $roles ) || empty( $roles ) ) {
			return true;
		}

		return false;
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
	 * @return Tribe_Roles
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

