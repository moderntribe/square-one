<?php


namespace Tribe\Project\User;


use Tribe\Project\Object_Meta\Meta_Map;
use Tribe\Project\Object_Meta\Meta_Repository;

/**
 * Class User_Object
 *
 * For most not-test usage, instances of this class
 * will be created via the `factory()` method called
 * on the subclasses.
 *
 * Instances can be used to access meta registered by
 * an appropriate Meta_Group, via the `get_meta()` method
 * called with a registered key.
 */
class User_Object {

	const NAME = 'users';

	/** @var Meta_Map */
	protected $meta;

	/** @var integer */
	protected $user_id = 0;

	/**
	 * User_Object constructor.
	 *
	 * @param int           $user_id        The ID of a User.
	 * @param Meta_Map|null $meta           Meta fields appropriate to a user.
	 */
	public function __construct( $user_id = 0, Meta_Map $meta = NULL ) {
		$this->user_id = $user_id;
		if ( isset( $meta ) ) {
			$this->meta = $meta;
		} else {
			$this->meta = new Meta_Map( self::NAME );
		}
	}

	public function __get( $key ) {
		return $this->get_meta( $key );
	}

	/**
	 * Get the value for the given meta key corresponding
	 * to this user.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get_meta( $key ) {
		$user = sprintf( 'user_%s', $this->user_id );
		return $this->meta->get_value( $user, $key );
	}

	/**
	 * Get an instance of the User_Object corresponding
	 * to the user with the given $user_id
	 *
	 * @param int $user_id The ID of an existing user
	 * @return static
	 */
	public static function factory( $user_id ) {
		/** @var Meta_Repository $meta_repo */
		$meta_repo = apply_filters( Meta_Repository::GET_REPO_FILTER, NULL );
		if ( ! $meta_repo ) {
			$meta_repo = new Meta_Repository();
		}

		$user = new static( $user_id, $meta_repo->get( self::NAME ) );

		return $user;
	}
}